﻿<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
/*
	*功能：付完款后跳转的页面（页面跳转同步通知页面）
	*版本：3.1
	*日期：2010-10-29
	'说明：
	'以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	'该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
	
*/
///////////页面功能说明///////////////
//该页面可在本机电脑测试
//该页面称作“页面跳转同步通知页面”，是由支付宝服务器同步调用，可当作是支付完成后的提示信息页，如“您的某某某订单，多少金额已支付成功”。
//可放入HTML等美化页面的代码和订单交易完成后的数据库更新程序代码
//该页面可以使用PHP开发工具调试，也可以使用写文本函数log_result进行调试，该函数已被默认关闭，见alipay_notify.php中的函数return_verify
//TRADE_FINISHED(表示交易已经成功结束，为普通即时到帐的交易状态成功标识);
//TRADE_SUCCESS(表示交易已经成功结束，为高级即时到帐的交易状态成功标识);
///////////////////////////////////

require_once("class/alipay_notify.php");
require_once("../config/config.php");
require_once("../config/alipay_config.php");
//构造通知函数信息
$alipay = new alipay_notify($partner,$key,$sign_type,$_input_charset,$transport);
//计算得出通知验证结果
$verify_result = $alipay->return_verify();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
    $dingdan           = $_GET['out_trade_no'];    //获取订单号
    $total_fee         = $_GET['total_fee'];	    //获取总价格

    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') 
    {
		//判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序

	}
    else 
    {
      	echo "trade_status=".$_GET['trade_status'];
    }
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else 
{
    //验证失败
    //如要调试，请看alipay_notify.php页面的return_verify函数，比对sign和mysign的值是否相等，或者检查$veryfy_result有没有返回true
    echo "fail";
}
?>

<title><?php echo SERVER_NAME;?>赞助充值-充值完成</title>
	
<style type="text/css">
            .font_content{
                font-family:"宋体";
                font-size:18px;
                color:#FF6600;
            }
            .font_title{
                font-family:"宋体";
                font-size:16px;
                color:#FF0000;
                font-weight:bold;
            }
            table{
                border: 1px solid #CCCCCC;
            }
</style>
        
<script language=javascript>
function countDown(secs,surl)
{
 	tiao.innerText=secs;
 	if(secs>0)
 	{
 	 	secs--;
     	setTimeout("countDown("+secs+",'"+surl+"')",1000);
 	}
 	else
 	{   
 		window.open(surl,'_parent'); 
 	} 
}
</script>



</head>
    <body>
    	<p align="center"><?php echo SERVER_NAME;?>-赞助充值系统</p>
        <table align="center" width="450" cellpadding="5" cellspacing="0">
            <tr>
                <td align="center" class="font_title" colspan="2">
           			 <p><span id="tiao">15</span>秒后自动返回到付款页。<script language=javascript>countDown(15,"<?php echo $show_url;?>");</script></p>
					 <p><a href="<?php echo $show_url;?>" target="_parent">如果没反应请点击这里返回</a></p>
				</td>
            </tr>
            <tr>
                <td class="font_content" align="right" >支付宝交易号：</td>
                <td class="font_content" align="left"><?php echo $_GET['trade_no']; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">订单号：</td>
                <td class="font_content" align="left"><?php echo $_GET['out_trade_no']; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">付款总金额：</td>
                <td class="font_content" align="left"><?php echo $_GET['total_fee']; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">充入账户：</td>
                <td class="font_content" align="left"><?php echo $_GET['subject']; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">冲入商城币：</td>
                <td class="font_content" align="left"><?php echo $_GET['total_fee'] * $pointRate; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">付款账号：</td>
                <td class="font_content" align="left"><?php echo $_GET['buyer_email']; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">交易状态：</td>
                <td class="font_content" align="left">
<?php
    	if($_GET['trade_status'] == "TRADE_SUCCESS" || $_GET['trade_status'] == "TRADE_FINISHED" )
    		echo "交易成功";
		else
			echo "交易失败";
?>
     	 		</td>
            </tr>
        </table>
    </body>
</html>