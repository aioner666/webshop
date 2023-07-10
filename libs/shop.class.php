<?php

/* ================================
  Autor: 灰色枫叶
  ================================ */

class shop extends Factory {

    function shop() {
        $this->elem = array(
            "object_id" => null,
            "item_id" => null,
            "item_count" => null,
            "item_price" => null,
            "category" => null,
            "sub_category" => null,
            "list" => null,
            "sales_ranking" => null,
            "item_type" => null,
            "gift" => null,
            "title_description" => null,
            "description" => null,
            "expire_day" => null
        );
    }
    
    /**
     * 获取一个页码的物品
     */
    function getItems($page,$categoryId,$sub_categoryId) {
        $sql = "select * from " . MYSQL_BASE_GS . ".ingameshop";
        $offset = ($page-1) * ITEM_NUM_IN_PAGE;
        if($categoryId == -1){
            $sql .= " order by object_id desc limit " . mysql_real_escape_string($offset) . "," . ITEM_NUM_IN_PAGE;
        }else if ($sub_categoryId == -1) {
            $sql .= " where category=" . mysql_real_escape_string($categoryId) . " order by object_id desc limit " . mysql_real_escape_string($offset) . "," . ITEM_NUM_IN_PAGE;
        } else {
            $sql .= " where category=" . mysql_real_escape_string($categoryId) . " AND sub_category=" . mysql_real_escape_string($sub_categoryId) . " order by object_id desc limit " . mysql_real_escape_string($offset) . "," . ITEM_NUM_IN_PAGE;
        }
        return $this->PopulateList($sql);
    }
    /**
     * 获取物品总数
     * @param type $categoryId
     * @param type $SubCategoryId
     * @return type
     */
    function getItemsCount($categoryId, $SubCategoryId) {
        $sql = "SELECT COUNT(*) as count FROM " . MYSQL_BASE_GS . ".ingameshop";
        if ($categoryId > -1) {
            $sql.=" where category=" . mysql_real_escape_string($categoryId);
        }
        if ($SubCategoryId > 2) {
            $sql .= " AND sub_category=" . mysql_real_escape_string($SubCategoryId);
        }
        $query = mysql_query($sql, $GLOBALS["conn"]) or die(mysql_error());
        $total = mysql_fetch_array($query);
        mysql_free_result($query);
        return $total['count'];
    }
    
    /**
     * 搜索物品
     * @param type $ar
     * @param type $page
     * @return type
     */
    function SearchByItemName($ar, $page) {
        $offset = ($page-1) * ITEM_NUM_IN_PAGE;
        $sql = "select * from " . MYSQL_BASE_GS . ".ingameshop where title_description like '%".mysql_real_escape_string($ar)."%' order by object_id desc limit " . mysql_real_escape_string($offset) . "," .ITEM_NUM_IN_PAGE;
        return $this->PopulateList($sql);
    }
    /**
     * 搜索物品总量
     * @param type $ar
     * @return type
     */
    function SearchItemCount($ar) {
        $sql = "SELECT COUNT(*) as count FROM " . MYSQL_BASE_GS . ".ingameshop";
        $sql.= " where title_description like '%".mysql_real_escape_string($ar)."%'";
        $query = mysql_query($sql, $GLOBALS["conn"]) or die(mysql_error());
        $total = mysql_fetch_array($query);
        mysql_free_result($query);
        return $total['count'];
    }
    
    /**
     * 通过物品ID查询物品
     * @param 物品ID $itemId
     * @return 物品
     */
    function getItemByItemId($itemId) {
        $sql = "SELECT * FROM " . MYSQL_BASE_GS .".ingameshop where item_id = '".mysql_real_escape_string($itemId)."'";
        return $this->PopulateObject($sql);
    }
    /**
     * 通过物品唯一ID查询物品
     * @param type $objId
     * @return type
     */
    function getItemByItemObjectId($objId) {
        $sql = "SELECT * FROM " . MYSQL_BASE_GS .".ingameshop where object_id = '".mysql_real_escape_string($objId)."'";
        return $this->PopulateObject($sql);
    }

    /*
     * 查询物品相关的图标名字
     */
    function getItemIcon($item_id) {
        $sql = "SELECT item_icon FROM ".MYSQL_BASE_WEB.".item_icons where item_id='".mysql_real_escape_string($item_id)."'";
        $query = mysql_query($sql, $GLOBALS["conn"]) or die(mysql_error());
        $total = mysql_fetch_array($query);
        mysql_free_result($query);
        return $total[0];
    }
}

?>