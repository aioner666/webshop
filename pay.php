<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>在线商城</title>
        <meta name="keywords" content="在线商城,灰色枫叶" />
        <meta name="description" content="最完美最好玩的永恒之塔！" />
        <link href="css/hsfy.css" rel="stylesheet"/>
        <script src="js/aionroy.js"></script>
        <script src="js/jquery-1.8.3.min.js"></script>
        <script src="js/layer/layer.min.js"></script>
        <script src="js/layer/extend/layer.ext.js"></script>
    </head>
    <body>
        <?php require_once("config/alipay_config.php"); ?>
        <div class="templatemo_content margin_right_10">
            <div class="content_section">
                
                <SCRIPT language=JavaScript>
                    function changePoints(){
                        var money = document.getElementById("alimoney").value;
                        document.getElementById("shoppoints").value = money * "<?php echo $pointRate; ?>";
                    }

                    function CheckForm(){
                        if (document.alipayment.accoutid.value === "") {
                            alert("请输入需要充值的游戏账号");
                            document.alipayment.accoutid.focus();
                            return false;
                        } else if (document.alipayment.accoutid1.value === "") {
                            alert("请输入确认的游戏账号");
                            document.alipayment.accoutid1.focus();
                            return false;
                        } else if (document.alipayment.accoutid.value !== document.alipayment.accoutid1.value) {
                            alert("两次输入的账号不同！（账号是你的游戏账号）");
                            document.alipayment.accoutid.focus();
                            return false;
                        } else if (document.alipayment.alimoney.value.length === 0) {
                            alert("请输入付款金额.");
                            document.alipayment.alimoney.focus();
                            return false;
                        }

                        var reg = new RegExp(/^\d*\.?\d{0,2}$/);
                        if (!reg.test(document.alipayment.alimoney.value)){
                            alert("请正确输入付款金额");
                            document.alipayment.alimoney.focus();
                            return false;
                        }

                        if (Number(document.alipayment.alimoney.value) < 0.01) {
                            alert("付款金额金额最小是10元.");
                            document.alipayment.alimoney.focus();
                            return false;
                        }

                        setTimeout("reload()",2000);//延时3秒 
                    }
                    
                    
                    
                </SCRIPT>
                
                <div id="alipay">
                    <h1>支付宝在线充值</h1>
                    <form name="alipayment" onSubmit="return CheckForm();" action="alipayto.php" method="post" target="_blank">
                        <input type="text" name="accoutid" id="accoutid" class="username" placeholder="请输入您的帐号！" onfocus="inputBox(this, '请输入您的游戏帐号')">
                        <input type="text" name="accoutid1" id="accoutid1" class="username" placeholder="请确认您的帐号！" onfocus="inputBox(this, '请确认您的游戏帐号')">
                        <input type="text" name="alimoney"  placeholder="请输入要充值的金额！" onfocus="inputBox(this, '请输入要充值金额')" id="alimoney" onblur="changePoints()"/>
                        <input type="text" name="shoppoints" id="shoppoints" disabled="disabled" placeholder="商城币会根据输入金额自动计算"/>
                        <table>
                            <tr>
                                <td colspan="1" width="30%"><input style="float:right;" type="radio" name="pay_bank" value="directPay" checked></td>
                                <td colspan="2"  width="200"><img alt="" style="float:left;" src="images/pay/alipay_1.gif" border="0"/></td>
                            </tr>
                            <tr>
                                <td><input type="radio" name="pay_bank" value="ICBCB2C"/><img src="images/pay/ICBC_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="CMB"/><img src="images/pay/CMB_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="CCB"/><img src="images/pay/CCB_OUT.gif" border="0"/></td>
                            </tr>
                            <tr>
                                <td><input type="radio" name="pay_bank" value="ABC"/><img src="images/pay/ABC_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="COMM"/><img src="images/pay/COMM_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="SPDB"/><img src="images/pay/SPDB_OUT.gif" border="0"/></td>
                            </tr>
                            <tr>
                                <td><input type="radio" name="pay_bank" value="CITIC"/><img src="images/pay/CITIC_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="CIB"/><img src="images/pay/CIB_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="SDB"><img src="images/pay/SDB_OUT.gif" border="0"/></td>
                            </tr>
                            <tr>
                                <td><input type="radio" name="pay_bank" value="CMBC"/><img src="images/pay/CMBC_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="HZCBB2C"/><img src="images/pay/HZCB_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="SHBANK"/><img src="images/pay/SHBANK_OUT.gif" border="0"/></td>
                            </tr>
                            <tr>
                                <td><input type="radio" name="pay_bank" value="SPABANK"/><img src="images/pay/SPABANK_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="ICBCBTB"/><img src="images/pay/ENV_ICBC_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="CCBBTB"/><img src="images/pay/ENV_CCB_OUT.gif" border="0"/></td>
                            </tr>
                            <tr>
                                <td><input type="radio" name="pay_bank" value="SPDBB2B"/><img src="images/pay/ENV_SPDB_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="ABCBTB"/><img src="images/pay/ENV_ABC_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="PSBC-DEBIT"/><img src="images/pay/PSBC_OUT.gif" border="0"/></td>
                            </tr>
                            <tr>
                                <td><input type="radio" name="pay_bank" value="BOCB2C"><img src="images/pay/BOC_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="fdb101"/><img src="images/pay/FDB_OUT.gif" border="0"/></td>
                                <td><input type="radio" name="pay_bank" value="CEBBANK"/><img src="images/pay/CEB_OUT.gif" border="0"/></td>
                            </tr> 
                        </table>
                        <button type="submit" class="submit_button" name="nextstep" onkeydown="checkEnterKey(this)" onmouseover="inputBox(this, '点击提交订单后会转向支付宝网站进行后续付款')">提交订单</button>
                        <div class="cleaner"></div>
                    </form>
                    <div class="cleaner"></div>
                </div>

            </div>
        </div>
    </body>
</html>
