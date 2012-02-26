<?php
define('SELECT', 'SELECT');
define('INSERT', 'INSERT');
define('UPDATE', 'UPDATE');
define('DELETE', 'DELETE');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DB
 *
 * @author smarkoski
 */
class DB {

    private static $instance;
    private $mysqli;
    private $stmt;
    private $insertID;

    protected function __construct() {
        $this->mysqli = mysqli_init();
        if (!$this->mysqli->real_connect(Configuration::read('db_host'), Configuration::read('db_username'), Configuration::read('db_password'), Configuration::read('db_name'))) {
            throw new MysqliConnectionException('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }
    }
    
    /**
     *
     * @return DB
     */
    public static function instance() {
        if (!isset(self::$instance)) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function query($query, $parameterTypes, $parameters, $queryType='') {
        if ($queryType == ''){
            $queryType = $this->autoTypeQuery($query);
        }
        $this->stmt = $this->mysqli->prepare($query);
        if (!$this->stmt) {
            throw new MysqliMalformedQueryException($this->mysqli->error);
        }
        $this->bindParameters($parameterTypes, $parameters);
        $result = TRUE;
        switch ($queryType){
            case SELECT:
                $result = $this->bindResults();
                break;
        }
        $this->stmt->execute();
        $this->insertID = $this->stmt->insert_id;

        return $result;
    }

    private function bindParameters($parameterTypes, $parameters) {
        $bind_names[] = implode($parameterTypes);
        for ($i = 0; $i < count($parameters); $i++) {
            $bind_name = 'bind' . $i;
            $$bind_name = $parameters[$i];
            $bind_names[] = &$$bind_name;
        }
        call_user_func_array(array($this->stmt, 'bind_param'), $bind_names);
    }

    private function bindResults(){
        //Get information on the query result
        $resultMetadata = $this->stmt->result_metadata();

        //Create an array where this query result will live
        $resultArray = array();

        //Now loop through the field names and build out an array with those names
        while ($field = $resultMetadata->fetch_field())
        {
            $resultArray[$field->name] = NULL;
            $bindParameters[] = &$resultArray[$field->name];
        }

        //Now call the bind_result function on $stmt like we normally would, except now the
        //parameters are all built for us automatically!
        call_user_func_array(array($this->stmt, 'bind_result'), $bindParameters);

        return $resultArray;
    }

    private function autoTypeQuery($query){
        $queryTerms = preg_split('/\s+/', $query);
        return strtoupper(array_shift($queryTerms));
    }

}
?>
