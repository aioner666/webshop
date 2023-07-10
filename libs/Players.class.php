<?php

/* ================================
  Autor: Jorge Luiz Oliveira Borba
  Email: jorge.borba@gmail.com
  ================================ */

/**
 * Fun鏾es de persistencia de dados para a tabela players.
 *
 * @author Jorge Luiz Oliveira Borba <jorge.borba@gmail.com>
 * @copyright Copyright 2009, R3direct
 * @version $Revision: 1.0 $ $Date: 20/02/10 $
 * @package libs
 * @subpackage Players
 */
$tipo_race['ELYOS'] = "Elyos";
$tipo_race['ASMODIANS'] = "Asmodian";
$tipo_class['WARRIOR'] = "WARRIOR";
$tipo_class['GLADIATOR'] = "GLADIATOR";
$tipo_class['TEMPLAR'] = "TEMPLAR";
$tipo_class['SCOUT'] = "SCOUT";
$tipo_class['ASSASSIN'] = "ASSASSIN";
$tipo_class['RANGER'] = "RANGER";
$tipo_class['MAGE'] = "MAGE";
$tipo_class['SORCERER'] = "SORCERER";
$tipo_class['SPIRIT_MASTER'] = "SPIRIT_MASTER";
$tipo_class['PRIEST'] = "PRIEST";
$tipo_class['CLERIC'] = "CLERIC";
$tipo_class['CHANTER'] = "CHANTER";

class Players extends Factory {

    /**
     * Construtor da Classe, carrega o array elem com as propriedades da classe.
     *
     * @return void
     * @package libs
     * @subpackage Players
     * @version 1.0
     */
    function Players() {
        $this->elem = array(
            "id" => null,
            "name" => null,
            "account_id" => null,
            "account_name" => null,
            "exp" => null,
            "recoverexp" => null,
            "x" => null,
            "y" => null,
            "z" => null,
            "heading" => null,
            "world_id" => null,
            "gender" => null,
            "race" => null,
            "player_class" => null,
            "creation_date" => null,
            "deletion_date" => null,
            "last_online" => null,
            "cube_size" => null,
            "bind_point" => null,
            "title_id" => null,
            "rebirth_id" => null,
            "memberpoints" => null,
            "online" => null,
            "note" => null,
        );
    }
    
    function isExistName($name){
        $sql = "select * FROM ".MYSQL_BASE_GS .".players where name = '". mysql_real_escape_string($name)."'";
        return $this->PopulateObject($sql);
    }

    function SelectNames($id) {
        $sql = "select * FROM " . MYSQL_BASE_GS . ". players where account_id = " . mysql_real_escape_string($id);
        $query = mysql_query($sql, $GLOBALS["conn"]) or die(mysql_error());
        return $query;
    }

    /**
     * Carrega o Objeto com os dados referente ao $id.
     * @param string $id c骴igo do registro.
     * @return object|false
     * @package libs
     * @subpackage Players
     * @version 1.0
     */
    function SelectById($name) {
        $sql = "SELECT id,
		               name,
		               account_id,
		               account_name,
		               exp,
		               recoverexp,
		               x,
		               y,
		               z,
		               heading,
		               world_id,
		               gender,
		               race,
		               player_class,
		               creation_date,
		               deletion_date,
		               last_online,
		               cube_size,
		               bind_point,
		               title_id,
					   rebirth_id,
					   memberpoints,
		               online,
		               note
		          FROM " . MYSQL_BASE_GS . ".players				  
		         WHERE name = " . mysql_real_escape_string($name);

        return $this->PopulateObject($sql);
    }

    function getTotalPlayers() {
        $total = 0;
        $sql = "SELECT count(*) as total
		          FROM " . MYSQL_BASE_GS . ".players ";
        $query = mysql_query($sql, $GLOBALS["conn"]) or die(mysql_error());
        if ($rs = mysql_fetch_assoc($query)) {
            $total = $rs["total"];
        }
        mysql_free_result($query);
        return $total;
    }

    function getTotalPlayersOnline() {
        $total = 0;
        $sql = "SELECT count(*) as total
		          FROM " . MYSQL_BASE_GS . ".players 
		         WHERE online = 1
		         ";
        $query = mysql_query($sql, $GLOBALS["conn"]) or die(mysql_error());
        if ($rs = mysql_fetch_assoc($query)) {
            $total = $rs["total"];
        }
        mysql_free_result($query);
        return $total;
    }

    function getTotalPlayersByRace($race) {
        $total = 0;
        $sql = "SELECT count(*) as total
		          FROM " . MYSQL_BASE_GS . ".players 
		         WHERE race = '" . $race . "'
		         ";
        $query = mysql_query($sql, $GLOBALS["conn"]) or die(mysql_error());
        if ($rs = mysql_fetch_assoc($query)) {
            $total = $rs["total"];
        }
        mysql_free_result($query);
        return $total;
    }

    /**
     * Carrega o Objeto com os dados enviados por POST.
     * @return void
     * @package libs
     * @subpackage Players
     * @version 1.0
     */
    function LoadByPost() {
        if ($_POST != "") {
            $this->PopulateByPost();
        }
    }

    /**
     * Carrega o Objeto com os dados enviados por GET.
     * @return void
     * @package libs
     * @subpackage Players
     * @version 1.0
     */
    function LoadByGet() {
        if ($_GET != "") {
            $this->PopulateByGet();
        }
    }

    /**
     * Carrega Lista de Registros.
     * @return array|false
     * @package libs
     * @subpackage Players
     * @version 1.0
     */
    function LoadLista($order = "1 DESC") {
        if ($_GET["pag"] > 0)
            $this->pag = $_GET["pag"];

        $sql = "SELECT id,
                   name,
                   account_id,
                   account_name,
                   exp,
                   recoverexp,
                   x,
                   y,
                   z,
                   heading,
                   world_id,
                   gender,
                   race,
                   player_class,
                   creation_date,
                   deletion_date,
                   last_online,
                   cube_size,
                   bind_point,
                   title_id,
				   rebirth_id,
				   memberpoints,
                   online,
                   note
		          FROM " . MYSQL_BASE_GS . ".players
		         WHERE 1 = 1 ";

        $this->Populate($sql, $order);
    }

    /**
     * Carrega Um ComboList com Lista de Registros.
     * @return array|false
     * @package libs
     * @subpackage Players
     * @version 1.0
     */
    function LoadCombo($value = "") {
        $sql = "SELECT id,
                   name,
                   account_id,
                   account_name,
                   exp,
                   recoverexp,
                   x,
                   y,
                   z,
                   heading,
                   world_id,
                   gender,
                   race,
                   player_class,
                   creation_date,
                   deletion_date,
                   last_online,
                   cube_size,
                   bind_point,
                   title_id,
				   rebirth_id,
				   memberpoints,
                   online,
                   note
		          FROM " . MYSQL_BASE_GS . ".players
		      ORDER BY 1";

        $this->PopulateCombo($sql, $value);
    }
}

?>