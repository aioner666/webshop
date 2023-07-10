<?php

require_once("alipay_function.php");
class alipay_service {
var $gateway;
var $_key;
var $mysign;
var $sign_type;
var $parameter;
var $_input_charset;
function alipay_service($parameter,$key,$sign_type) {
$this->gateway		= "https://www.alipay.com/cooperate/gateway.do?";
$this->_key  		= $key;
$this->sign_type	= $sign_type;
$this->parameter	= para_filter($parameter);
if($parameter['_input_charset'] == '')
$this->parameter['_input_charset'] = 'utf8';
$this->_input_charset   = $this->parameter['_input_charset'];
$sort_array   = arg_sort($this->parameter);
$this->mysign = build_mysign($sort_array,$this->_key,$this->sign_type);
}
function build_form() {
$sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->gateway."_input_charset=".$this->parameter['_input_charset']."' method='get'>";
while (list ($key,$val) = each ($this->parameter)) {
$sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
}
$sHtml = $sHtml."<input type='hidden' name='sign' value='".$this->mysign."'/>";
$sHtml = $sHtml."<input type='hidden' name='sign_type' value='".$this->sign_type."'/>";
$sHtml = $sHtml."<input type='submit' value='确认充值'></form>";
$sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
return $sHtml;
}
}
?>