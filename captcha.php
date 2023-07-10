<?php
require_once("libs/Captcha.class.php");
$aFonts = array("fonts/VeraBd.ttf", "fonts/VeraIt.ttf", "fonts/Vera.ttf");
$oVisualCaptcha	= new PhpCaptcha($aFonts, 130, 30);
$oVisualCaptcha -> Create();
?>