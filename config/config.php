<?php
/*================================
  灰色枫叶 
  ================================*/
session_start();
set_time_limit(0); 
header("Content-Type:text/html; charset=utf-8",true); //网页编码


//网站标题 
define("SERVER_NAME", "上古永恒"); ////网站标题
define("weburlc", "http://localhost/");//网站地址
define("bbs_url", "#");//论坛地址

/* 
*系统设置 
*/ 
define("LANGUAGE", "chs"); //语言
define("IP_HOST", "127.0.0.1"); //服务器IP 可以设置为自己的外网IP 一般不用改 

//MYSQL数据库设置 
define("MYSQL_HOST", "127.0.0.1:3366"); // 数据库地址 
define("MYSQL_USER", "root"); // 数据库帐号 
define("MYSQL_PASS", "aionroy"); // 数据库密码 
//永恒服务器LS、GS端口设置
define("GS_HOST", "7777"); // 服务器端口 
define("LS_HOST", "2106"); // 游戏登陆端口 
//永恒服务器 数据库的表名
define("MYSQL_BASE_LS", "eridian_ls"); // LoginServer 数据库名
define("MYSQL_BASE_GS", "eridian_gs"); // GameServer 数据库名
define("MYSQL_BASE_WEB", "eridian_web"); // 网站数据库名

//WEBSHOP 配置
//每页显示多少个商品（建议不要随便改）
define("ITEM_NUM_IN_PAGE", "10");

//控制每次购买商品时间间隔（默认20秒）
define("HSFY_BUY_TIME_LIMIT", "20");

//是否刚打开商城时候显示全部物品
define("HSFY_SHOW_ALLITEM", false);
/*
* 系统相关参数
*/
setlocale(LC_NUMERIC, 0);
setlocale(LC_ALL, "Portuguese_Brazil","PT");
setlocale(LC_TIME, "pt_BR.iso-8859-1");

//错误报告
error_reporting(E_ALL ^ E_NOTICE);

//显示日志和错误
ini_set("log_errors", 0);
ini_set("display_errors", 0);

//error_function 必需。规定发生错误时运行的函数。 
//error_types 可选。规定在哪个错误报告级别会显示用户定义的错误。默认是 "E_ALL"。 
set_error_handler("writeLog", E_ALL ^ E_NOTICE);

/*
 * PHP防注入，检测并关闭PHP全局变量
 */
if(ini_get('register_globals') == 1){
    ini_set('register_globals', 0);
}

/**
 * 日志输出
 */
function writeLog()
{
	$log = "";
	list($id, $erro, $arquivo, $linha, $detalhes) = func_get_args();
	$log .= "------ 灰色枫叶 时间: " . date("H:i:s") . " 客户IP: " . $_SERVER['REMOTE_ADDR']. " ------\n\n";
	$log .= "错误：" . $erro . "\n\n";
	$log .= "文件: " . $arquivo . "\n\n";
	$log .= "LINHA: " . $linha . "\n\n";
	$log .= "引用: " . $_SERVER["HTTP_REFERER"] . "\n\n";
	$log .= "POST   : " . var_export($detalhes["_POST"], TRUE) . "\n\n";
	$log .= "GET    : " . var_export($detalhes["_GET"], TRUE) . "\n\n";
	$log .= "FILE    : " . var_export($detalhes["_FILES"], TRUE) . "\n\n";
	$log .= "SESSION    : " . var_export($detalhes["_SESSION"], TRUE) . "\n\n";
	$log .= "COOKIE    : " . var_export($detalhes["_COOKIE"], TRUE) . "\n\n";
	$log .= "SERVER    : " . var_export($detalhes["_SERVER"], TRUE) . "\n\n";
	$fileName = "logs/" . date("Ymd") . "-log.txt";
	$file = fopen($fileName, 'a');
	fwrite($file, $log);
	fclose($file);
}

$conn = @mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die(mysql_error());
//mysql_select_db(MYSQL_BASE_WEB, $conn) or die(mysql_error());
mysql_query("set names utf8");
//自动加载
function __autoload($class_name){
    require_once 'libs/'.$class_name .'.class.php';
}
?>