<?php

/* ================================
  Autor: Jorge Luiz Oliveira Borba
  Email: jorge.borba@gmail.com
  ================================ */

/**
 * Fun珲es de Execus鉶 de SQL e carregamento de dados.
 *
 * @author Jorge Luiz Oliveira Borba <jorge.borba@gmail.com>
 * @copyright Copyright 2009, R3direct
 * @version $Revision: 1.5 $ $Date: 2009/04/03 12:48:19 $
 * @package libs
 * @subpackage Factory
 *
 * Essa classe funciona como um DaoFactory.
 * cria objetos de entidades dinamicamente.
 */
class Factory {

    var $elem;
    var $pag = 0;
    var $cont = 0;
    var $reg_pag = 0;
    var $dados;
    var $conn;

    /**
     * ?respons醰el por interceptar o retorno de uma propriedade.
     *
     * @param string $prop_name nome da propriedade ?qual deseja restaurar o valor.
     * @return string|NULL
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function __get($prop_name) {
        if (isset($this->elem[$prop_name])) {
            return $this->elem[$prop_name];
        } else {
            return $this->elem[$prop_name];
        }
    }

    /**
     * ?respons醰el por interceptar o setamento de valor de uma propriedade.
     *
     * @param string $prop_name nome da propriedade ?qual deseja setar o valor.
     * @param string $prop_value valor que deseja setar ?propriedade.
     * @return true|false
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function __set($prop_name, $prop_value) {
        if (array_key_exists($prop_name, $this->elem)) {
            $this->elem[$prop_name] = $prop_value;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Insere dados dinamicamente na tabela, atrav閦 das propriedades setadas na classe, e retorna o id.
     *
     * @param string $table nome da tabela referente 鄐 propriedades setadas.
     * @return integer|false
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function db_insert($table) {
        $sql = "INSERT INTO " . $table;
        $set = "";
        $value = "";
        $array = $this->elem;
        while ($bar = each($array)) {
            if (isset($bar["value"])) {
                if ($set == "") {
                    $set .= $bar["key"];
                    $value .= "'" . mysql_escape_string($bar["value"]) . "'";
                } else {
                    $set .= ", " . $bar["key"];
                    $value .= ", '" . mysql_escape_string($bar["value"]) . "'";
                }
            }
        }

        $sql .= "(" . $set . ")VALUES(" . $value . ")";
        $query = @mysql_query($sql, $GLOBALS["conn"]);
        if ($query) {
            return mysql_insert_id($GLOBALS["conn"]);
        }
        return false;
    }

    /**
     * Atualiza dados dinamicamente na tabela, atrav閦 das propriedades setadas na classe.
     *
     * @param string $table nome da tabela referente 鄐 propriedades setadas.
     * @param string $where filtro do update.
     * @return true|false
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function db_update($table, $where) {
        $sql = "UPDATE " . $table . " SET ";
        $set = "";
        $array = $this->elem;
        while ($bar = each($array)) {
            if (isset($bar["value"])) {
                if ($set == "") {
                    $set .= $bar["key"] . " = '" . mysql_escape_string($bar["value"]) . "'";
                } else {
                    $set .= ", " . $bar["key"] . " = '" . mysql_escape_string($bar["value"]) . "'";
                }
            }
        }
        if ($where != "") {
            $where = " WHERE " . $where;
        }
        $sql .= $set . $where;
        $query = mysql_query($sql, $GLOBALS["conn"]);
        return $query;
    }

    /**
     * Deleta dados dinamicamente na tabela, atrav閦 das propriedades setadas na classe.
     *
     * @param string $table nome da tabela referente 鄐 propriedades setadas.
     * @param string $where filtro do elete.
     * @return true|false
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function db_delete($table, $where) {
        $sql = "DELETE FROM " . $table;
        if ($where != "") {
            $where = " WHERE " . $where;
        }
        $sql .= $where;

        $query = mysql_query($sql, $GLOBALS["conn"]);

        return $query;
    }

    /**
     * Carrega um array com os dados da tabela.
     *
     * @param string $sql comando SQL que popular?o array.
     * @param string $order filtro do select.
     * @return null
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function Populate($sql, $order = "1 DESC") {
        if ($_GET["pag"] > 0) {
            $this->pag = $_GET["pag"];
        }
        $array = $this->elem;
        while ($bar = each($array)) {
            if ($bar["value"] != "") {
                if (is_numeric($bar["value"])) {
                    $sql .= " AND " . $bar["key"] . " = '" . mysql_escape_string($bar["value"]) . "' ";
                } else {
                    $sql .= " AND " . $this->like($bar["key"], $bar["value"]);
                }
            }
        }

        $sql .= " ORDER BY " . $order;
        $query = mysql_query($sql, $GLOBALS["conn"]);
        while ($linha = mysql_fetch_array($query)) {
            $dados[] = $this->stripslashes_deep($linha);
        }
        $this->dados = $dados;
        mysql_free_result($query);
    }
    
    /****
     * 根据GET获取参数，查询数据库，返回数组
     */
    function PopulateList($sql) {
       // alerta($sql);
        $query = mysql_query($sql, $GLOBALS["conn"]);
        while ($linha = mysql_fetch_array($query)) {
            $dados[] = $this->stripslashes_deep($linha);
        }
     //   $this->dados = $dados;
        mysql_free_result($query);
        return $dados;
    }
    /**
     * Carrega a classe com os dados do banco de dados.
     *
     * @param string $sql comando SQL que popular?a classe.
     * @return object|false
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function PopulateObject($sql) {
        $array = $this->elem;
        $query = mysql_query($sql, $GLOBALS["conn"]);
        $rs = mysql_fetch_assoc($query);
        if ($rs) {
            while ($bar = each($array)) {
                $array[$bar["key"]] = $this->trata_aspas(trim($rs[$bar["key"]]));
            }
        } else {
            return false;
        }
        mysql_free_result($query);
        $this->elem = $array;
        return $this->elem;
    }

    /**
     * Carrega a classe com os dados enviados por POST.
     *
     * @return array
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function PopulateByPost() {
        $array = $this->elem;
        while ($bar = each($array)) {
            if (isset($_POST[$bar["key"]])) {
                $array[$bar["key"]] = strip_tags(trim($_POST[$bar["key"]]));
            }

            if (isset($_POST["html_" . $bar["key"]])) {
                $array[$bar["key"]] = trim($_POST["html_" . $bar["key"]]);
            }
        }

        $this->elem = $array;

        return $this->elem;
    }

    /**
     * Carrega a classe com os dados enviados por GET.
     *
     * @return array
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function PopulateByGet() {
        $array = $this->elem;
        while ($bar = each($array)) {
            if (isset($_GET[$bar["key"]])) {
                $array[$bar["key"]] = strip_tags(trim($_GET[$bar["key"]]));
            }
            if (isset($_GET["html_" . $bar["key"]])) {
                $array[$bar["key"]] = trim($_GET["html_" . $bar["key"]]);
            }
        }
        $this->elem = $array;
        return $this->elem;
    }

    /**
     * Carrega os options de um combo box com dados da tabela.
     * @param string $sql comando SQL que popular?a classe.
     * @param string $value valor marcado pelo combo.
     * @return array
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function PopulateCombo($sql, $value = "") {
        $query = mysql_query($sql, $GLOBALS["conn"]);
        while ($rs = mysql_fetch_array($query)) {
            $selected = "";
            if (is_array($value)) {
                if (in_array($rs[0], $value)) {
                    $selected = "selected";
                }
            }else {
                if ($value == $rs[0]) {
                    $selected = "selected";
                }
            }
            echo "<option value=\"" . $rs[0] . "\" " . $selected . ">" . $rs[1] . "</option>";
        }
        mysql_free_result($query);
    }

    /**
     * Fun玢o para busca independente de acentua玢o e letras maiusculas/minusculas.
     *
     * @return string
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function like($campo, $valor) {
        $sql = "     replace(UCASE(" . $campo . "), ' , 'AEIOUAEIOUAOAEIOOAEIOUC') 
			    LIKE 
				     replace(UCASE('%" . mysql_escape_string($valor) . "%'), ' , 'AEIOUAEIOUAOAEIOOAEIOUC') ";
        return $sql;
    }

    /**
     * Fun玢o para converter um texto para um texto no formato permalink
     * @param string $texto texto que ser?convertido
     * @return string
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function ConvertToPermalink($texto) {
        $valor = strtolower($texto);
        //Remove Caracteres Especiais
        $carac_especiais = array(
            '&quot;' => '',
            '!' => '',
            '@' => '',
            '#' => '',
            '$' => '',
            '%' => '',
            '^' => '',
            '&' => '',
            '*' => '',
            '(' => '',
            ')' => '',
            '_' => '',
            '+' => '',
            '{' => '',
            '}' => '',
            '|' => '',
            ':' => '',
            '"' => '',
            '<' => '',
            '>' => '',
            '?' => '',
            '[' => '',
            ']' => '',
            '\\' => '',
            ';' => '',
            "'" => '',
            ',' => '',
            '/' => '',
            '*' => '',
            '+' => '',
            '~' => '',
            '`' => '',
            '=' => '');

        $valor = str_replace(array_keys($carac_especiais), array_values($carac_especiais), $valor);

        //Remove Acentos
        $a = array(
            "/ /" => "-",
            "/[忏噌鋆/" => "a",
            "/[觇殡]/" => "e",
            "/[铐祜]/" => "i",
            "/[趱蝮鯹/" => "o",
            "/[]/" => "u",
            "/?" => "c");

        $valor = preg_replace(array_keys($a), array_values($a), $valor);

        return $valor;
    }

    /**
     * Fun玢o para formatar datas que ser鉶 inseridas no banco.
     *
     * @return string
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function prepara_data($data) {
        if ($data != "") {
            $d1 = explode("/", $data);
            $d1 = implode("-", array_reverse($d1));
        }
        return $d1;
    }

    /**
     * Fun玢o para tratamento de aspas que ser鉶 inseridas no banco.
     *
     * @return string
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function trata_aspas($texto) {
        return stripslashes($texto);
    }

    /**
     * Fun玢o para exibir aspas sem barra que foram inseridas no banco.
     *
     * @return string
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function stripslashes_deep($value) {
        $value = is_array($value) ?
                array_map(array($this, "stripslashes_deep"), $value) :
                stripslashes($value);

        return $value;
    }

    /**
     * Fun玢o fun玢o que popula um array com as linhas de resultado.
     *
     * @return array|false
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function Rs() {
        $this->cont = $this->cont + 1;
        if ($this->reg_pag > 0) {
            if ($this->cont <= $this->reg_pag) {
                if (!$this->dados[$this->pag + ($this->cont - 1)]) {
                    return false;
                }
                $dados = $this->dados[$this->pag + ($this->cont - 1)];
                return $dados;
            } else {
                return false;
            }
        } else {
            $dados = $this->dados[($this->cont - 1)];
            return $dados;
        }
    }

    /**
     * Retorna a quantidade de registros retonado pela query.
     *
     * @return integer
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function TotalRegistros() {
        return count($this->dados);
    }

    /**
     * Retorna a quantidade de p醙inas retonado pela query.
     *
     * @return integer
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function TotalPaginas() {
        if ($this->reg_pag > 0) {
            return ceil($this->TotalRegistros() / $this->reg_pag);
        } else {
            return 1;
        }
    }

    /**
     * Retorna a p醙ina atual de registros.
     *
     * @return integer
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function PaginaAtual() {
        if ($this->reg_pag > 0) {
            for ($x = 0; $x <= $this->TotalPaginas(); $x++) {
                $pag = (($x - 1) * $this->reg_pag);
                if ($this->pag == $pag) {
                    return $x;
                }
            }
        }
        return 1;
    }

    /**
     * Verifica se existe pr髕ima p醙ina de registros.
     *
     * @return true|false
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function TemProximo() {
        if ($this->reg_pag > 0) {
            if (($this->pag + $this->reg_pag) < $this->TotalRegistros()) {
                return true;
            } else {
                return false;
            }
        }else {
            return false;
        }
    }

    /**
     * Verifica se existe p醙ina anterior de registros.
     *
     * @return true|false
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function TemAnterior() {
        if ($this->pag > 1) {
            return true;
        } else
            return false;
    }

    /**
     * Retorna a url para a pr髕ima p醙ina de dados.
     *
     * @return string
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function LinkProximo() {
        return($_SERVER["PHP_SELF"] . "?pag=" . ($this->pag + $this->reg_pag));
    }

    /**
     * Retorna a url para p醙ina de dados anterior.
     *
     * @return string
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function LinkAnterior() {
        return($_SERVER["PHP_SELF"] . "?pag=" . ($this->pag - $this->reg_pag));
    }

    /**
     * Retorna o numero da p醙ina anterior.
     *
     * @return integer
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function PagAnterior() {
        $link = ((($this->pag) - ($this->cont - 1)) - $this->reg_pag);
        $link = $this->pag - ($this->cont - 1);
        $link = $link - $this->reg_pag;
        return($link);
    }

    /**
     * Retorna o n鷐ero da pr髕ima p醙ina.
     *
     * @return integer
     * @package libs
     * @subpackage Factory
     * @version 1.0
     */
    function PagProximo() {
        return($this->pag);
    }

}

?>