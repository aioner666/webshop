<?php

/* ================================
  Autor: Jorge Luiz Oliveira Borba
  Email: jorge.borba@gmail.com
  ================================ */

/**
 * Fun鏾es de persistencia de dados para a tabela ".MYSQL_BASE_LS.".account_data.
 *
 * @author Jorge Luiz Oliveira Borba <jorge.borba@gmail.com>
 * @copyright Copyright 2009, R3direct
 * @version $Revision: 1.0 $ $Date: 01/02/10 $
 * @package libs
 * @subpackage AccountData
 */
class AccountData extends Factory {

    /**
     * Construtor da Classe, carrega o array elem com as propriedades da classe.
     *
     * @return void
     * @package libs
     * @subpackage AccountData
     * @version 1.0
     */
    function AccountData() {
        $this->elem = array(
            "id" => null,
            "name" => null,
            "password" => null,
            "activated" => null,
            "access_level" => null,
            "membership" => null,
            "last_server" => null,
            "last_ip" => null,
            "credits" => null
        );
    }

    /**
     * Carrega o Objeto com os dados referente ao $id.
     * @param string $id c骴igo do registro.
     * @return object|false
     * @package libs
     * @subpackage AccountData
     * @version 1.0
     */
    function SelectById($id) {
        $sql = "SELECT id,
		               name,
		               password,
		               activated,
		               access_level,
		               membership,
		               last_server,
		               last_ip,
		    		   credits
		          FROM " . MYSQL_BASE_LS . ".account_data
		         WHERE id = " . mysql_real_escape_string($id);

        return $this->PopulateObject($sql);
    }

    function FazerLogin() {
        $sql = "SELECT id,
		               name,
		               password,
		               activated,
		               access_level,
		               membership,
		               last_server,
		               last_ip,
		    		   credits
		          FROM " . MYSQL_BASE_LS . ".account_data
				 WHERE activated = 1
				   AND name = '" . mysql_real_escape_string($this->name) . "'
				   AND password = '" . mysql_real_escape_string($this->password) . "'";

        if ($this->PopulateObject($sql)) {
            return true;
        } else {
            return false;
        }
    }

    function isPassword() {
        $sql = "SELECT id,
		               name,
		               password,
		               activated,
		               access_level,
		               membership,
		               last_server,
		               last_ip,
		               credits
		          FROM " . MYSQL_BASE_LS . ".account_data
				 WHERE activated = 1
				   AND id = '" . mysql_real_escape_string($this->id) . "'
				   AND password = '" . mysql_real_escape_string($this->password) . "'";

        if ($this->PopulateObject($sql)) {
            return true;
        } else {
            return false;
        }
    }

    function CanInsert() {
        $sql = "SELECT name
		          FROM " . MYSQL_BASE_LS . ".account_data
		         WHERE name = '" . mysql_escape_string($this->name) . "'";

        $query = mysql_query($sql, $GLOBALS["conn"]);

        if ($rs = mysql_fetch_assoc($query)) {
            return false;
        }

        return true;
    }

    function getTotalAccounts() {
        $total = 0;

        $sql = "SELECT count(*) as total
		          FROM " . MYSQL_BASE_LS . ".account_data 
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
     * @subpackage AccountData
     * @version 1.0
     */
    function LoadByPost() {
        if ($_POST != "") {
            $this->PopulateByPost();

            if ($this->password != "")
                $this->password = cryptPassword($this->password);
        }
    }

    /**
     * Carrega o Objeto com os dados enviados por GET.
     * @return void
     * @package libs
     * @subpackage AccountData
     * @version 1.0
     */
    function LoadByGet() {
        if ($_GET != "") {
            $this->PopulateByGet();

            if ($this->password != "")
                $this->password = cryptPassword($this->password);
        }
    }

    /**
     * Insere no banco de dados os dados carregados no objeto.
     * @return boolean
     * @package libs
     * @subpackage AccountData
     * @version 1.0
     */
    function Insert() {
        return $this->db_insert(MYSQL_BASE_LS . ".account_data");
    }

    /**
     * Atualiza no banco de dados os dados carregados no objeto.
     * @return boolean
     * @package libs
     * @subpackage AccountData
     * @version 1.0
     */
    function Update() {
        $where = "id = " . mysql_real_escape_string($this->id);
        return $this->db_update(MYSQL_BASE_LS . ".account_data", $where);
    }

    /**
     * Carrega Lista de Registros.
     * @return array|false
     * @package libs
     * @subpackage AccountData
     * @version 1.0
     */
    function LoadLista($order = "1 DESC") {
        if ($_GET["pag"] > 0)
            $this->pag = $_GET["pag"];

        $sql = "SELECT id,
                   name,
                   password,
                   activated,
                   access_level,
		               membership,
                   last_server,
                   last_ip
		          FROM " . MYSQL_BASE_LS . ".account_data
		         WHERE 1 = 1 ";

        $this->Populate($sql, $order);
    }

    /**
     * Carrega Um ComboList com Lista de Registros.
     * @return array|false
     * @package libs
     * @subpackage AccountData
     * @version 1.0
     */
    function LoadCombo($value = "") {
        $sql = "SELECT id,
                   name,
                   password,
                   activated,
                   access_level,
		               membership,
                   last_server,
                   last_ip
		          FROM " . MYSQL_BASE_LS . ".account_data
		      ORDER BY 1";

        $this->PopulateCombo($sql, $value);
    }

}

?>