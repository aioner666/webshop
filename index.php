<?php 
/*================================
  Autor: 灰色枫叶 QQ:93900604
  Web: www.moyaion.com
  ================================*/
?>
<!doctype html>
<html>
<head>
<title>灰色枫叶在线商城</title>
<script src="js/jquery-1.8.3.min.js"></script>
<script src="js/layer/layer.min.js"></script>
<style>
html{background-color:#E3E3E3; font-size:14px; color:#000; font-family:'微软雅黑'}
</style>
</head>
<body>

<script>
$(function(){
$.layer({
    type: 2,
    bgcolor: '#111',
    fadeIn: 900,
    shade: [0],
    fix: false,
    title: '灰色枫叶在线商城',
    maxmin: true,
    iframe: {src : 'shop.php' ,scrolling: 'no'},
    area: ['800px' , '635px'],
    close: function(index){
        layer.msg('您真的不要再买东西了吗？如果需要请按F5刷新即可重新打开商城！',3,1);
    }
}); 
});
</script>
</body>
</html>