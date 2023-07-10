/*******
 * 复制到剪贴板 灰色枫叶
 * @param {type} obj
 * @returns {undefined}
 */
function CopyItemInfo(obj) {
    clipboardData.setData("Text", "[item:" + obj + "]");
    alert("复制物品信息成功，请粘贴到游戏的聊天窗口可查看物品详情！");

}


function firm() {
    if (confirm("商城币不足，购买失败！是否前去充值？")) {
        var index = parent.layer.getFrameIndex(window.name); //获取当前窗体索引
        parent.layer.iframeSrc(index, "index.php?pay=1");
    }
}

function reload() {
    var index = parent.layer.getFrameIndex(window.name); //获取当前窗体索引
    parent.layer.iframeSrc(index, "webshop/shop.php");
    //parent.layer.close(index); 
}

function CheckREG(){
     if (document.loginform.login_account.value === "") {
             alert("请输入游戏账号");
             document.loginform.login_account.focus();
             return false;
     }  else if (document.loginform.login_account.value.length < 3) {
          alert("账户长度不能小于3位");
          document.loginform.login_account.focus();
          return false;
     } else if (document.loginform.login_password.value === "") {
          alert("请输入确认的游戏密码");
          document.loginform.login_password.focus();
          return false;
     } else if (document.loginform.input_img.value==="") {
         alert("请输入验证码");
        document.loginform.input_img.focus();
        return false;
      } 
}


/*****
 * 导航栏切换
 * @param {type} num
 * @param {type} count
 * @returns {undefined}
 */
function navChange(num, count) {
    for (var id = 0; id <= count; id++) {
        if (id === num) {
            document.getElementById("qh_con" + id).style.display = "block";
            document.getElementById("mynav" + id).className = "nav_on";
        } else {
            if (document.getElementById("qh_con" + id) !== null)
                document.getElementById("qh_con" + id).style.display = "none";
            if (document.getElementById("mynav" + id) !== null)
                document.getElementById("mynav" + id).className = "nav_off";
        }
    }
}
/*****
 * 二级分类
 * @param {type} ca
 * @param {type} sub
 * @returns {undefined}
 */
function navSubChange(ca, sub) {
    document.getElementById("sub" + ca + sub).className = "checked";
    //alert("sub" + ca + sub + "   ");
}

/****
 * 赠送礼物，输入名字 灰色枫叶
 * @param {type} itemid
 * @param {type} page
 * @returns {undefined}
 */
function GiftSend(itemid, page) {
    layer.prompt({title: '请输入您要赠送的玩家名字'}, function(name) {
        ButItem(itemid, name, true, page);
    });
}

/**
 * 显示角色选择列表 灰色枫叶
 * @returns {undefined}
 */
function showDIV() {
    $.layer({
        type: 1,
        shade: [0],
        area: ['auto', 'auto'],
        title: false,
        border: [0],
        page: {dom: '#player_info'}
    });
//还可用layer.confirm()快捷调用
}

function BuyConfirm() {
    alert("optt.name");
    $.layer({
        shade: [0],
        area: ['auto', 'auto'],
        dialog: {
            msg: '您确定要购买该商品吗？',
            btns: 2,
            type: 4,
            btn: ['确认购买', '我不想买了'],
            yes: function() {
                layer.msg('购买成功', 1, 1);
            }, no: function() {
                layer.msg('购买失败', 1, 13);
            }
        }
    });
}

/**
 * 购买商品 灰色枫叶
 * @param {type} PARAMS
 * @param {type} NAME
 * @param {type} gift
 * @param {type} page
 * @returns {undefined}
 */
function ButItem(PARAMS, NAME, gift, page) {
    var temp = document.createElement("form");
    temp.action = "shop.php?" + page;
    temp.method = "post";
    temp.style.display = "none";
    var opt = document.createElement("textarea");
    opt.name = "BuyitemId";
    opt.value = PARAMS;
    temp.appendChild(opt);
    var optt = document.createElement("textarea");
    if (gift) {
        optt.name = "giftplayername";
    }
    else {
        optt.name = "playername";
    }
    optt.value = NAME;
    //alert(optt.name)
    temp.appendChild(optt);
    document.body.appendChild(temp);
    temp.submit();
}

/***
 * 设置当前角色 灰色枫叶
 * @param {type} name
 * @returns {undefined}
 */
function CharName(name, urls) {
    var temp = document.createElement("form");
    temp.action = "shop.php?" + urls;
    temp.method = "post";
    temp.style.display = "none";
    var opt = document.createElement("textarea");
    opt.name = "playername";
    opt.value = name; //document.getElementById('selectChar').value;
    // alert(opt.value)
    temp.appendChild(opt);
    document.body.appendChild(temp);
    temp.submit();
}


function checkEnterKey(obj) {
    if (event.keyCode === 13) {
        obj.click();
    }
}
/**
 * 搜索物品
 * @returns {undefined}
 */
var serchCount = 0;
function SearchByName() {
    var object = document.getElementById('searchiteminput');
    if (object.value === "") {
        var arrayObj = ["你是在逗我吗？请输入一个关键词！", "警告过你了！", "你还要玩是吗？那我奉陪！", "你肯定是个穷鬼，没钱买商品啊！", "你觉得这样很好玩吗？", "行,你赢了！俺不陪你了！"];
        var arrayColor = ["c00", "0c0", "00c", "c24", "2cf", "f5c"];
        var arrayNum = [0, 3, 2, 0, 3, 1];
        layer.tips(arrayObj[serchCount], object, {
            style: ['background-color:#' + arrayColor[serchCount] + '; color:#fff; font-size:14px; ', '#' + arrayColor[serchCount]],
            time: serchCount === 5 ? 30 : 3,
            guide: arrayNum[serchCount]
        });
        serchCount++;
        if (serchCount > 5) {
            serchCount = 0;
        }
        return;
    }
    var temp = document.createElement("form");
    temp.action = "shop.php";
    temp.method = "post";
    temp.style.display = "none";
    var opt = document.createElement("textarea");
    opt.name = "soitem";
    opt.value = object.value;
    //alert(opt.value)
    temp.appendChild(opt);
    document.body.appendChild(temp);
    temp.submit();
}

/**
 * 物品预览显示 灰色枫叶
 * @param {type} obj
 * @param {type} itemname
 * @param {type} dis
 * @returns {undefined}
 */
function msgBox(obj, itemname, dis) {
    var len = (itemname.length + 4) * 12 + 30;
    if (len < 150)
        len = 160;
    layer.tips('<br/><img src=' + obj.src + ' width=120px /><br/>名称：' + itemname + '<br/>描述：' + dis + '<br/><font color="#000000">提醒：点击商品名字可复制商品代码，粘贴到游戏聊天窗口可查看商品详情！</font>', obj, {
        style: ['background-color:#fd7d3d; color:#fff; font-size:12px;', '#fd7d3d'],
        maxWidth: len,
        time: 2,
        closeBtn: [0, true]
    });
}
function inputBox(obj, msg) {
    //  alert(msg);
    layer.tips(msg, obj, {
        guide: 3,
        style: ['background-color:#c00; color:#fff; font-size:14px; ', '#c00'],
        time: 5
    });
}
function regBox(obj, msg) {
    //  alert(msg);
    layer.tips(msg, obj, {
        guide: 0,
        style: ['background-color:#07c; color:#fff; font-size:14px; ', '#07c'],
        time: 5
    });
}


function RegAccount() {
    var tilte = document.getElementById("login_title");
    var name = document.getElementById("login_account");
    var psw = document.getElementById("login_password");
    var code = document.getElementById("input_img");
    var login = document.getElementsByClassName("submit_button")[0];
    var linkmsg = document.getElementById("link_msg");
    var regtext = document.getElementById("regtext");
//    var text = document.getElementById('link_msg').innerHTML
//    或
//    var text =$("#link_msg").val();
    if (regtext.innerHTML === "注册") {
        $('#login_account').bind("focus", function() {
            regBox(this, '注册帐号只支持英文和数字');
        });
        $('#login_password').bind("focus", function() {
            regBox(this, '请输入准备注册帐号的密码');
        });
        $('#input_img').bind("focus", function() {
            regBox(this, '请输入右侧的验证码');
        });

        tilte.innerHTML = "帐号注册";
        name.placeholder = "请输入需要注册的用户帐号";
        psw.placeholder = "请输入需要注册的用户密码";
        code.name = "reg_code";
        login.innerHTML = "注册帐号";
        
        linkmsg.innerHTML = "已经有账号了？点我<a href=\"javascript:void(0)\" onclick=\"RegAccount()\" id=\"regtext\">登陆</a>";
    } else {
        name.addEventListener('focus', function() {
            inputBox(this, '请输入您的游戏帐号');
        }, false);
        psw.addEventListener('focus', function() {
            inputBox(this, '请输入您的用户密码');
        }, false);
        code.addEventListener('focus', function() {
            inputBox(this, '请输入右侧的验证码');
        }, false);
        
        tilte.innerHTML = "帐号登录";
        name.placeholder = "请输入您的用户名";
        psw.placeholder = "请输入您的用户密码";
        code.name = "user_code";
        login.innerHTML = "立刻登录";
        linkmsg.innerHTML = "还没有帐号吗？点我<a href=\"javascript:void(0)\" onclick=\"RegAccount()\" id=\"regtext\">注册</a>";
    }
}

function updateCaptcha() {
    var oo = document.getElementById("captcha_img");
    oo.src = "Captcha.php";
}


