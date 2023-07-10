<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReadXML
 *
 * @author 灰色枫叶
 */
class ReadXML extends Factory{

    //put your code here
    function ReadXML() {
        $this->elem = array(
            "count" => null, //主分类数量
            "oneNav" => null,//主分类ID 和  NAME
            "twoNav" => null//主分类ID 和 次分类数组
        );
    }

    function Read($xmlfile) {
        $doc = new DOMDocument();
        $doc->load($xmlfile);
        $xmlcategorys = $doc->getElementsByTagName("category");

        $xmlcategorys_count = 0;
        //echo $categorys_count;
        foreach ($xmlcategorys as $xmlcategory) {
            $xmlcategorys_count++;
            $xmlsub_categorys = $xmlcategory->getElementsByTagName("sub_category");
            foreach ($xmlsub_categorys as $xmlsub) {
                $xmlsub_categoryId = $xmlsub->getAttribute('id');
                $xmlsub_categoryName = $xmlsub->getAttribute('name');
                $state[$xmlsub_categoryId] = $xmlsub_categoryName;
                //echo $sub_categoryId.$sub_categoryName;
            }

            $xmlcategoryId = $xmlcategory->getAttribute('id');
            $xmlcategoryName = $xmlcategory->getAttribute('name');
            
            
            //第一个节点数据 ID 和 NAME
            $oneState[$xmlcategoryId] = $xmlcategoryName;
            //第二个节点数据  【第一个ID， 第2个节点数组】
            $twoState[$xmlcategoryId] = $state;
            
            $state = null;
        }
        $this->count = $xmlcategorys_count;
        $this->oneNav = $oneState;
        $this->twoNav = $twoState;
    }

}

?>
