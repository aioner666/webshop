<?php

/* ================================
  Autor: 灰色枫叶 QQ:93900604
  Web: www.moyaion.com
  ================================ */
require_once("config/config.php");
require_once("config/language.php");
require_once("libs/Captcha.class.php");
$page = 1;
$categoryId = -1;
$sub_categoryId = -1;

if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
    $page = intval($_GET["page"]);
}
if (isset($_GET["categoryId"]) && is_numeric($_GET["categoryId"])) {
    $categoryId = intval($_GET["categoryId"]);
}
if (isset($_GET["sub_categoryId"]) && is_numeric($_GET["sub_categoryId"])) {
    $sub_categoryId = intval($_GET["sub_categoryId"]);
    if ($sub_categoryId > 2) {
        unset($_SESSION["so_item_name"]);
    }
}
$url_for_get = "page=" . $page . "&categoryId=" . $categoryId . "&sub_categoryId=" . $sub_categoryId;

//当前是否为赠送的角色名
$isGiftName = FALSE;

$POSTNAME = filter_input(INPUT_POST, "playername", FILTER_SANITIZE_STRING);
$POSTPLAYERNAME = filter_input(INPUT_POST, "giftplayername", FILTER_SANITIZE_STRING);
if ($POSTNAME) {
    $_SESSION["playername"] = $POSTNAME;
} else if ($POSTPLAYERNAME) {
    $_SESSION["playername"] = $POSTPLAYERNAME;
    $isGiftName = TRUE;
}

//购买POST
if (isset($_POST["BuyitemId"]) && is_numeric($_POST["BuyitemId"])) {
    $post_item_object_id = intval($_POST["BuyitemId"]);
    $canBuy = false;
    if ($_SESSION["timess"] != "") {
        $a = time() - $_SESSION["timess"];
        $canBuy = ($a > HSFY_BUY_TIME_LIMIT);
    } else {
        $_SESSION["timess"] = time();
        $canBuy = true;
    }
    // echo alerta($a.'   '.$_SESSION["timess"]);
    if ($_SESSION["playername"] == "") {
        alerta(HSFY_SELECT_A_PLAYER);
        echo '<script>location.href="shop.php"</script>';
    }

    if ($canBuy && $_SESSION["id"] != "" && $_SESSION["last_item_objectid"] != $post_item_object_id) {
        $sItem = new shop();
        $sItem->getItemByItemObjectId($post_item_object_id);

        $cannot_sendgift = false;
        if ($isGiftName) {
            if ($sItem->item_price == 0) {
                $cannot_sendgift = true;
                echo "<script>layer.alert('" . HSFY_CANNOT_SEND_FREE_ITEM . "', 3,  '" . HSFY_ITEM_SEND_FAIL . "')</script>";
            } else { //如果可以赠送，那么再检查一次角色名是否存在
                $toSendName = $_SESSION["playername"];
                $SendPlayer = new Players();
                $checkExist = $SendPlayer->isExistName($toSendName);
                if (!$checkExist) {
                    $cannot_sendgift = true;
                    echo "<script>layer.alert('[" . $toSendName . "]" . HSFY_PLAYER_NOT_EXIST . "', 3,  '" . HSFY_ITEM_SEND_FAIL . "')</script>";
                }
            }
        }
        if (!$cannot_sendgift) {
            $Account = new AccountData();
            $Account->SelectById($_SESSION["id"]);

            if ($Account->credits - $sItem->item_price >= 0) {
                $Account->credits = $Account->credits - $sItem->item_price;
                $Account->Update();
                //增加购买物品到数据库
                $Ritem = new AccountWebshop();
                $Ritem->item_id = $sItem->item_id;
                $Ritem->item_count = $sItem->item_count;
                $Ritem->accountId = $Account->id;
                $Ritem->playername = $_SESSION["playername"];
                $Ritem->Insert();
                //增加两次购买判断
                $_SESSION["last_item_objectid"] = $post_item_object_id;

                $canBuy = false;
                $_SESSION["timess"] = time();
                if (!$isGiftName) {
                    echo "<script>layer.alert('[" . $_SESSION["playername"] . "]，" . HSFY_CONGRATULATION_SUCCESS . $sItem->item_count . HSFY_JIAN . $sItem->title_description . "," . HSFY_WAIT_TO_CHECK_EMAIL . "', 1,  '" . HSFY_BUY_ITEM_SUCCESS . "')</script>";
                } else {
                    echo "<script>layer.alert('" . HSFY_SEND . "[" . $_SESSION["playername"] . "]" . $sItem->item_count . HSFY_JIAN . $sItem->title_description . HSFY_SUCCESS . "," . HSFY_WAIT_TO_CHECK_EMAIL . "', 1,  '" . HSFY_SEND_ITEM_SUCCESS . "')</script>";
                }
                //alerta("恭喜你[".$Ritem->playername."],商品购买成功,请稍后登录邮箱查收！");
            } else {
                echo '<script>firm()</script>';
            }
        }
    } else {
        if ($_SESSION["last_item_objectid"] == $post_item_object_id) {
            //alerta("请不要重复刷新网页或者连续购买同一个商品！");
            echo '<script>layer.alert("' . HSFY_DONT_BUY_AT_SAMETIME . '", 3,  "' . HSFY_WARN . '")</script>';
        } else if ($_SESSION["id"] == "") {
            //alerta("请先登录！");
            echo '<script>layer.alert("' . HSFY_ACCOUNT_NOT_EXIST . '", 3,  "' . HSFY_LOGIN_FIRST . '")</script>';
        } else {
            //alerta("请不要连续购买! 再次购买还剩".(20-$a)."秒");
            $msgs = HSFY_DO_NOT_BUY_CONTINIU . (HSFY_BUY_TIME_LIMIT - $a) . HSFY_SECOND;
            echo '<script>layer.alert("' . $msgs . '", 3,  "' . HSFY_WARN . '")</script>';
        }
    }
    if ($isGiftName) {
        unset($_SESSION["playername"]);
        $isGiftName = FALSE;
    }
    //echo '<script>location.href="shop.php"</script>';
}

if (isset($_POST["user_code"]) && is_string($_POST["user_code"])) {
    if (PhpCaptcha::Validate($_POST['user_code'])) {
        $objAccountData = new AccountData();
        $objAccountData->LoadByPost();
        if ($objAccountData->FazerLogin()) {
            $_SESSION["id"] = $objAccountData->id;
            $_SESSION["name"] = $objAccountData->name;
            $_SESSION["access_level"] = $objAccountData->access_level;
            alerta(HSFY_LOGIN_SUCCESS);
            echo '<script>location.href="shop.php"</script>';
            exit;
        } else {
            $msg = HSFY_LOGIN_FAIL;
        }
    } else {
        $msg = HSFY_CODE_WRONG;
    }

    if ($msg != "") {
        echo '<script>layer.alert("' . $msg . '", 3,  "' . HSFY_SOMETHING_WRONG_WITH_LOGIN . '")</script>';
    }
} else if (isset($_POST["reg_code"]) && is_string($_POST["reg_code"])) {
    if (PhpCaptcha::Validate($_POST['reg_code'])) {
        $objAccountData = new AccountData();
        $objAccountData->LoadByPost();
        if ($objAccountData->CanInsert()) {
            if ($objAccountData->Insert()) {
                alerta("[" . $objAccountData->name . "]" . HSFY_REG_SUCCESS);
                echo '<script>location.href="shop.php"</script>';
            } else {
                $msg = HSFY_REG_FAIL;
            }
        } else {
            $msg = HSFY_REG_FAIL;
        }
    } else {
        $msg = HSFY_CODE_WRONG;
    }
    if ($msg != "") {
        echo '<script>layer.alert("' . $msg . '", 3,  "' . HSFY_WARN . '")</script>';
    }
}

function BuyItem() {
    
}

function isOnline($port) {
    $errno = null;
    $errstr = null;
    if (fsockopen(IP_HOST, $port, $errno, $errstr, 1)) {
        return true;
    } else {
        return false;
    }
}

//解密
function cryptPassword($password) {
    if ($password != "") {
        return mysql_escape_string(base64_encode(pack("H*", sha1(utf8_encode($password)))));
    }
}

//弹出信息
function alerta($msg) {
    echo "<script>alert(\"" . $msg . "\");</script>";
}

//返回
function voltar() {
    echo "<script>history.back();</script>";
}

?>