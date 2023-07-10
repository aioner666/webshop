<?php

/** 
【灰色枫叶帮助：如何获取安全校验码和合作身份者ID ？】

1.访问支付宝商户服务中心(b.alipay.com)，然后用您的签约支付宝账号登陆.
2.访问“技术服务”→“下载技术集成文档”（https://b.alipay.com/support/helperApply.htm?action=selfIntegration）
3.在“自助集成帮助”中，点击“合作者身份(Partner ID)查询”、“安全校验码(Key)查询”

*/

//■■■■■■■■■■■■■■■请在这里配置您的基本信息■■■■■■■■■■■■■■■

//充值的积分比例，例如：100 表示玩家每充值 1 元人民币，玩家账户中充入 100 点乌云贸易商城币
$pointRate 		= "10";

//支付宝合作身份者ID，以2088开头的16位纯数字
$partner		= "2088002009457302";

//安全检验码，以数字和字母组成的32位字符
$key			= "c4wvzltc29dtlnhc3y7cxdiw8hfq5xs8";

//签约支付宝账号或卖家支付宝帐户
$seller_email	= "keerroy@foxmail.com";

//交易过程中服务器通知的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
$notify_url		= "http://182.254.153.254:88/webshop/payinc/notify_url.php";

//付完款后跳转的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
$return_url		= "http://182.254.153.254:88/webshop/payinc/return_url.php";
 
//充值页地址，不允许加?id=123这类自定义参数
$show_url		= "http://182.254.153.254:88/index.php";

//收款方名称，如：公司名称、网站名称、收款人姓名等
$mainname		= "上古永恒";

//■■■■■■■■■■■■■■■请在这里配置您的基本信息■■■■■■■■■■■■■■■


//签名方式 不需修改
$sign_type		= "MD5";

//字符编码格式 目前支持 GBK 或 utf-8
$_input_charset	= "utf-8";

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$transport		= "http";

?>