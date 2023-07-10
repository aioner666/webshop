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
<?php require_once("libs/functions.php");?>
 <div class="templatemo_content margin_right_10">
     <div class="content_section">
        <?php if($_SESSION["id"] == ""){ ?>
        <div id="page-container">
            <h1 id="login_title">帐号登录</h1>
            <form name="loginform" action="shop.php" method="post" onSubmit="return CheckREG();">
                <input type="text" name="name" class="username" placeholder="请输入您的用户名！" onfocus="inputBox(this,'请输入您的游戏帐号')" id="login_account">
                <input type="password" name="password" class="password" placeholder="请输入您的用户密码！" onfocus="inputBox(this,'请输入您的游戏密码')" id="login_password">
                <input name="user_code" class="captcha" placeholder="请输入验证码！" onfocus="inputBox(this,'请输入右侧的验证码')"  id="input_img">
                <img src="captcha.php" height="42" alt="验证码" id="captcha_img" onclick="updateCaptcha()"/>
                <button type="submit" class="submit_button" onkeydown="checkEnterKey(this)" id="login_button">立刻登录</button>
                <span id="link_msg">还没有帐号吗？点我<a href="javascript:void(0)" onclick="RegAccount()" id="regtext">注册</a></span>

                <div class="cleaner"></div>
            </form>
<script>
$(function(){
    layer.tips('还没有账号的赶快点我注册一个', $("#regtext"), {
        guide: 2,
        time: 10,
        style: ['background-color:#07c; color:#fff', '#07c'],
        maxWidth:150
    });
});
</script>
            <div class="copyright">&copy; CopyRight 灰色枫叶 Rights Reserved.</div>
            <div class="cleaner"></div>
        </div>
        	
        <?php }else{  
            $Account = new AccountData();
            $Account->SelectById($_SESSION["id"]);
            $player = new Players();
            $players = $player->SelectNames($_SESSION["id"]);
        ?>
         
        <div id="player_info" style="display :none"><p>请先选择一个角色：<br /></p>
              <?php 
              while($prow = mysql_fetch_array($players)){ 
                echo  '<button onclick=CharName(this.id,"'.$url_for_get.'") type="submit" id="'.$prow["name"].'">'.$prow["name"].'</button>';
              } ?>
        </div>

        <div id=menu_out>
            <div id=menu_in>
                <div id=menu>
                    <UL id=nav>
                        <?PHP
                        $xml = new ReadXML();
                        $xml->Read("in_game_shop.xml");
                        $xmlcategorys_count = $xml->count;
                        $ONENAV = $xml->oneNav;
                        $TWONAV = $xml->twoNav;
                        
                        //自动判断URL中GET的列表ID是否超过最大的数字，否则设置成最大的
                        if($categoryId > $xmlcategorys_count-1){
                            $categoryId = $xmlcategorys_count-1;
                        }
                        $subMax =  count($TWONAV[$categoryId]);
                        if($sub_categoryId > $subMax+2){
                            $sub_categoryId = $subMax+2;
                        }
                        //自动判断结束
                            
                        $navCount = 0;
                        foreach ($ONENAV as $oneId=>$oneName){
                            $navCount++;
                            if($navCount==1){
                                $classname = "nav_on";
                            }else{
                                $classname = "nav_off";
                            }
                            $navId = 'onmousedown="javascript:navChange('.$oneId.','.$xmlcategorys_count.')"';
                            $navId.= ' id="mynav'.$oneId.'"';
                            $navId.= ' class="'.$classname.'"';
                        ?>
                        <li><a href="javascript:void(0)" <?php echo $navId;?>><span><?php echo $oneName;?></span></a></li>
                        <?php if($navCount!=$xmlcategorys_count){ ?>
                        <LI class="menu_line"></LI>
                        <?php } }?>   
                    </UL>
                    <div id=menu_con>
                        <?php 
                         foreach ($TWONAV as $key1 => $value1) {
                            if($key1==0){
                                $className = "DISPLAY:block";
                            }else{
                                $className = "DISPLAY:none";
                            }
                            $idname = ' id="qh_con'.$key1.'"';
                            $idname.= ' style="'.$className.'"';
                        ?>
                        <div <?php echo $idname;?>>
                            <UL>
                                <?php 
                                $keycount=0; 
                                foreach($value1 as $key=>$value) { 
                                    $keycount++; 
                                    $aherf = 'href="shop.php?page=1&categoryId='.$key1.'&sub_categoryId='.$key.'"';
                                    $aherf .=' id="sub'.$key1.$key.'"';
                                ?>                  
                                <LI><a <?php echo $aherf;?>><span><?php echo $value;?></span></A></LI>
                                <?php if($keycount!=count($value1)){ ?>
                                <LI class=menu_line2></LI>   
                                <?php }}?>
                            </UL>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div> 
        <div class="margin_bottom_10 horizontal_divider"></div> <!-- 横线 -->
<?php

if($categoryId > -1){
   echo  '<script>navChange('.$categoryId.','.$xmlcategorys_count.');</script>';
}
if($sub_categoryId > 2){
   echo '<script>navSubChange('.$categoryId.','.$sub_categoryId.');</script>';
}

$shopItems = new shop();
//搜索开始
$GET_ITEMNAME = filter_input(INPUT_POST,"soitem",FILTER_SANITIZE_STRING);

//alerta($_SESSION["so_item_name"]."  ");
$isSearchMode = false;
if($_SESSION["so_item_name"]!=NULL || $GET_ITEMNAME){
    $categoryId = -1;//主要是控制 上一页和下一页 不带有分类参数
    $isSearchMode = true;
    if ($GET_ITEMNAME) {
        $_SESSION["so_item_name"] = $GET_ITEMNAME;
    }

    $numrows = $shopItems->SearchItemCount($_SESSION["so_item_name"]);
    //页数总数
    $pages=intval($numrows/ITEM_NUM_IN_PAGE);
    if($numrows % ITEM_NUM_IN_PAGE){
        $pages++;
    }
    //防止页码大于最大页
    if($page > $pages){
        $page = $pages;
    }
    $myrows =$shopItems->SearchByItemName($_SESSION["so_item_name"],$page); //物品数组
}else{
    //首次打开商城是否显示全部物品，$categoryId 初始设置=-1，也就是显示全部商品。否则那就改成$categoryId=0，显示第一个分类的物品
    if(HSFY_SHOW_ALLITEM == FALSE){
        if($categoryId==-1){
            $categoryId = 0;
            $sub_categoryId = 3;
        }
    }
    //条目总数量
    $numrows = $shopItems->getItemsCount($categoryId,$sub_categoryId);
    //页数总数
    $pages=intval($numrows/ITEM_NUM_IN_PAGE);
    if($numrows % ITEM_NUM_IN_PAGE){
        $pages++;
    }
    //防止页码大于最大页
    if($page > $pages){
        $page = $pages;
    }
    $myrows =$shopItems->getItems($page,$categoryId,$sub_categoryId);//物品数组
}

?>
<!-- 商品列表大框 -->
<div class="goods">
   <ul>
    <?php foreach ($myrows as $k=>$myrow){
        $itemname = $myrow[title_description];
        switch ($myrow[item_type]){
            case 0:$classname = "tags_normorn";break;//常规商品
            case 1:$classname = "tags_new";break;//新品
            case 2:$classname = "tags_hot";break;//热销商品
            case 3:$classname = "tags_sale";break;//促销
            case 4:$classname = "tags_off";break;//减免
            case 5:$classname = "tags_discount";break;//折扣
            default:$classname = "tags_normorn";break;
        }
        $p_price = $myrow[item_price];
        $gift=false; 
  	if($p_price==0){ 
            $p_price ="<font font-size='10px'>免费</font>";
            $classname = "tags_gift";
        }
        //商品图标
        $itemIcon = $shopItems->getItemIcon($myrow[item_id]);
        $img="icons/".$itemIcon."_64.png";
        if(!file_exists($img)){
            $img="icons/".$itemIcon.".png";
            if(!file_exists($img)){
                $img = "images/item.png";
            }
        }
        
        $itemObjectId = $myrow[object_id];
        $itemToName = $_SESSION["playername"];//物品发送到 角色名
        
        //赠送商品按钮
        $send_clickthing= "javascript:GiftSend(".$itemObjectId.",'".$url_for_get."')";
        //购买商品按钮
        $buy_clickthing = "javascript:showDIV()";
        if($itemToName!=""){
            $buy_clickthing = "javascript:ButItem(".$itemObjectId.",'".$itemToName."',false,'".$url_for_get."')";
        }
     ?> 

    <li>
        <div class="li_inner">
            <a class="img_wrap"><img alt="" src="<?php echo $img;?>" onmouseover="msgBox(this,'<? echo $myrow[title_description]; ?>','<? echo $myrow[description]; ?>')" /></a>
            <p class="buyBtn">
                <a href="javascript:void(0)" class="btnGift" onclick="<?php echo $send_clickthing;?>">赠送</a>
                <a href="javascript:void(0)" class="btnBuy" onclick="<?php echo $buy_clickthing;?>">购买</a>
            </p>
        </div>
        <p class="<?php if($isSearchMode){ echo 'item_name_s'; }else{ echo 'item_name';}?>"><a title="<?php echo $itemname; ?>" href="javascript:void(0)" <?php echo 'id="'.$itemObjectId.'"';?> onclick="CopyItemInfo('<?php echo $myrow[item_id];?>')"><?php echo $itemname; ?></a></p>
        <p class="item_count"><?php echo "商品数量：".$myrow[item_count]; ?></p>
        <p class="price"><span class="txt">Price</span>
            <?php if(($classname=="tags_sale"||$classname=="tags_discount" ||$classname=="tags_off") && $p_price>0){ $_price = 1.34 * $p_price;
               echo '<span class="price_before">'.$_price.'</span>';
            }?>
            <span class="price_now"><?php echo $p_price; ?></span>
        </p>
        <p class="tags">
            <a class="<?php echo $classname;?>" >&nbsp;</a>
        </p>
    </li>
    <?php }  ?>
 </ul>
    <input type="text" name="name" class="searchinput" placeholder="请输入商品名的关键词！" id="searchiteminput" onfocus="inputBox(this,'请输入查询关键词,比如：特务队长')">
    <button type="submit" onclick="SearchByName()" onkeydown="checkEnterKey(this)">搜索</button>
</div>

<div class="margin_bottom_10 horizontal_divider"></div> <!-- 横线 -->
<?php
//显示总页数
//搜索时，不显示

	echo "<div id='foot'><div class='pleft'> 总计".$numrows."个，共有".$pages."页(".$page."/".$pages.")</div>";
        echo "<div class='pright'>帐号：".$Account->name."&nbsp;&nbsp;&nbsp;商城币：".$Account->credits.'&nbsp;&nbsp;&nbsp;角色：'.$_SESSION["playername"]."</div>";
	//显示分页数
	//for($i=1;$i<=$pages;$i++)    
	//echo "<a href='pages.php?page=".$i."'>第".$i."页</a>";
	//显示转到页数
	echo  "<form action='shop.php' method='get'>";
 	//计算首页、上一页、下一页、尾页的页数值
	$first=1;
	$prev=$page-1;
	$next=$page+1;
	$end=$pages;

	if($prev <1){
            $prev = 1;
        }
	if($next >$pages){
            $next = $pages;
        }
        
        $firstGet = "<a href='shop.php?page=".$first."&categoryId=".$categoryId."&sub_categoryId=".$sub_categoryId."'>首页</a>&nbsp;";
        $prevGet = "<a href='shop.php?page=".$prev."&categoryId=".$categoryId."&sub_categoryId=".$sub_categoryId."'>上一页</a>&nbsp;";
        $nextGet = "<a href='shop.php?page=".$next."&categoryId=".$categoryId."&sub_categoryId=".$sub_categoryId."'>下一页</a>&nbsp;";
        $endGet = "<a href='shop.php?page=".$end."&categoryId=".$categoryId."&sub_categoryId=".$sub_categoryId."'>尾页</a>";
        if($categoryId == -1){
            $firstGet = "<a href='shop.php?page=".$first."'>首页</a>&nbsp;";
            $prevGet = "<a href='shop.php?page=".$prev."'>上一页</a>&nbsp;";
            $nextGet = "<a href='shop.php?page=".$next."'>下一页</a>&nbsp;";
            $endGet = "<a href='shop.php?page=".$end."'>尾页</a>";
        }
        if($page == 1){
            echo "首页&nbsp;上一页&nbsp;";
        }else{
            echo $firstGet;
            echo $prevGet;
        }
        
        if($page==$pages){
            echo "下一页&nbsp;尾页";
        }else{
            echo $nextGet;
            echo $endGet;
        }
	echo "</form>";
	echo "</div><div class='clear'></div></div>";
//    }else{
//        echo "<div align='center'>";
//	echo "总计".$serchcount."个，共有1页(1/1)";
//	echo "<form><a  href='shop.php'>返回</a></form>";
//	echo "</div>";
//    } 
}?>
    </div>
   </div>

</body>
</html>