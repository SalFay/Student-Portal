<?php

/**
 * Description of class
 *
 * @author Fayaz Khan
 */
class Database {

    /**
     *
     * @var type 
     */
    private $_connection;

    /**
     *
     * @var type 
     */
    private $_resultset;

    /**
     *
     * @var type 
     */
    private $_insertid;

    /**
     *
     * @var type 
     */
    private $_query_stmt;

    /**
     *
     * @var type 
     */
    private $_query_args = array();

    /**
     *
     * @var type 
     */
    private $_query_type;

    /**
     * 
     */
    public function __construct() {
// Set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        );
        try {
            if (DB_TYPE == "MYSQL") {
                $connection_string = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
                $this->_connection = new PDO($connection_string, DB_USER, DB_PASS, $options);
            } else {
                $connection_string = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
                $this->_connection = new PDO($connection_string, "", "", $options);
            }
        } catch (PDOException $e) {
            log_exception($e);
            sf_die($e->getMessage(), TRUE);
        }
    }

    /**
     * Destroy resultset and connection
     */
    function __destruct() {
        $this->_resultset = null;
        $this->_connection = null;
    }

    /**
     * 
     * @param type $stmt
     * @param type $args
     * @throws PDOException
     */
    public function query($stmt, $args = null) {
        try {
            $this->_resultset = $this->_connection->prepare($stmt);
            if ($args == null) {
                $this->_resultset->execute();
            } else {
                $this->_resultset->execute($args);
            }
            $this->_insertid = $this->_resultset->rowCount();
            ;
        } catch (PDOException $e) {
            throw $e;
        }
        return $this;
    }

    /**
     * 
     * @param type $stmt
     * @param type $args
     * @throws PDOException
     */
    public function runQuery($stmt, $args = null) {
        try {
            $this->_resultset = $this->_connection->prepare($stmt);
            if ($args == null) {
                $this->_resultset->execute();
            } else {
                $this->_resultset->execute($args);
            }
            $this->_insertid = $this->_connection->lastInsertId();
        } catch (PDOException $e) {
            throw $e;
        }
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function fetchObject() {
        return $this->_resultset->fetch(PDO::FETCH_OBJ);
    }

    /**
     * 
     * @return type
     */
    public function fetchArray() {
        return $this->_resultset->fetch(PDO::FETCH_BOTH);
    }

    /**
     * 
     * @return type
     */
    public function fetchArrayAssoc() {
        return $this->_resultset->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 
     * @return type
     */
    public function rowCount() {
        return $this->_insertid;
    }
    
    /**
     * 
     * @param type $stmt
     * @param type $field
     * @param type $args
     * @return mixed Value from the query, False otherwise
     * @throws PDOException
     */
    public function queryValue($stmt, $field, $args = array()){
        $db = new Database();
        try{
            $x = $db->query($stmt, $args);
            if($x->rowCount()>0){
                $output = $x->fetchObject()->$field;
            }
        }catch(PDOException $e){
            throw $e;
        }
        return $output;
    }
    
    /**
     * 
     * @return type
     */
    public function lastInsertId() {
        return $this->_insertid;
    }

    /**
     * 
     * @param type $table
     */
    public function insert($table) {
        $this->_query_stmt = "INSERT INTO $table";
        $this->_query_type = "INSERT";
        return $this;
    }

    /**
     * 
     * @param type $field
     * @param type $value
     */
    public function bind($field, $value) {
        $this->_query_args[$field] = $value;
        return $this;
    }

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function execute() {
        try {
            $stmt = $this->_query_stmt;
            switch ($this->_query_type) {
                case "INSERT":
                    $stmt .= "(" . implode(",", array_keys($this->_query_args)) . ")";
                    $marks = array();
                    foreach ($this->_query_args as $key) {
                        $marks[] = "?";
                    }
                    $marks = implode(",", $marks);
                    $stmt .= " VALUES($marks)";
                    break;
                default:
                    throw new Exception("Invalid Query type");
            }
            //echo $stmt;
            $this->runQuery($stmt, array_values($this->_query_args));
            return $this->lastInsertId();
        } catch (Exception $e) {
            throw $e;
        }
    }
}