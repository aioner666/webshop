﻿<?php
/*
	*功能：支付宝主动通知调用的页面（服务器异步通知页面）
	*版本：3.1
	*日期：2010-10-29
	'说明：
	'以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	'该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

*/
///////////页面功能说明///////////////
//创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
//该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
//该页面调试工具请使用写文本函数log_result，该函数已被默认关闭，见alipay_notify.php中的函数notify_verify
//TRADE_FINISHED(表示交易已经成功结束，通用即时到帐反馈的交易状态成功标志);
//TRADE_SUCCESS(表示交易已经成功结束，高级即时到帐反馈的交易状态成功标志);
//该服务器异步通知页面面主要功能是：对于返回页面（return_url.php）做补单处理。如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
/////////////////////////////////////

require_once("class/alipay_notify.php");
require_once("../config/alipay_config.php");
require_once("../config/config.php");

$alipay = new alipay_notify($partner,$key,$sign_type,$_input_charset,$transport);    //构造通知函数信息
$verify_result = $alipay->notify_verify();  //计算得出通知验证结果

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
    $dingdan           = $_POST['out_trade_no'];	//获取支付宝传递过来的订单号
    $total             = $_POST['total_fee'];		//获取支付宝传递过来的总价格
	$oldPoints		   = $_POST['extra_common_param'];
	$subject         = $_POST['subject'];
	
    if($_POST['trade_status'] == 'TRADE_FINISHED' ||$_POST['trade_status'] == 'TRADE_SUCCESS') {    //交易成功结束
		//判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		
		$arr = explode(" ",$subject);
		$accountId = $arr[1];
        $sql = "SELECT credits FROM ".MYSQL_BASE_LS.".account_data where name = '".mysql_real_escape_string($accountId)."'";
        $query = mysql_query($sql, $conn) or die('无法连接数据库: ' . mysql_error());
        $fetch = mysql_fetch_array($query);
        mysql_free_result($query);
        $nowPoints = $fetch[0];//获得当前账户点数
        
        //log_result($sql." ".$oldPoints." 商城币增加成 " .$nowPoints);
		if($oldPoints == $nowPoints)//判断充值前 和 当前的账户是否相等，如果相等就说明 付款后还没有进行增加相应的点数。
		{
			//设置对应账户中的点数
			$addpoints = $total * $pointRate;//充值数量 乘以 充值倍率
			$addSql = "UPDATE ".MYSQL_BASE_LS.".account_data SET credits = ".($oldPoints+$addpoints)." WHERE name =  '".$accountId."'";
			mysql_query($addSql,$conn);
			//log_result($addSql." ".$oldPoints." 商城币增加成 " .$addpoints);
		}

		echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //log_result("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
    else {
        echo "success";		//其他状态判断。普通即时到帐中，其他状态不用判断，直接打印success。

        //调试用，写文本函数记录程序运行情况是否正常
        //log_result ("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //log_result ("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>