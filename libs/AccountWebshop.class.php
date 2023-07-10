<?php

/* ================================
  Autor: 灰色枫叶
  ================================ */

class AccountWebshop extends Factory {

    function AccountWebshop() {
        $this->elem = array(
            "id" => null,
            "accountId" => null,
            "playername" => null,
            "item_id" => null,
            "item_count" => null,
            "rewarded" => null
        );
    }

    function Insert() {
        return $this->db_insert(MYSQL_BASE_LS . ".account_webshop");
    }

}

?>