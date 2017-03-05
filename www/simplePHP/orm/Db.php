<?php
namespace core\orm;
use PDO;
use PDOException;
use Exception;

class Db {
    public static $instance;

    public $db;
    private $sql;
    private $hasCreateAndUpdate;

    private $stark = [];

    protected $bind;
    protected $where = '1';
    protected $field;
    protected $insert;
    protected $order;
    protected $limit;
    protected $table;

    public function __construct($dbType = 'mysql', $info = []){
        $this->db = new PDO("$dbType:host=$info[host];port=$info[db_port];dbname=$info[db_name]", $info['db_admi'], $info[
            'db_pawd']);
    }
    public static function getInstance($dbType = 'mysql', $info = []){        
        if(!self::$instance instanceof self) {
            self::$instance = new self($dbType, $info);
        }
        return self::$instance;
    }

    public function setSql($isfind = false){
        if(empty($this->table)) {
            throw new Exception("表名不能为空");
            exit;
        }
        if(empty($this->sql)){
            $this->field = $this->field ?? "*";
    
            $sql = "SELECT {$this->field} FROM {$this->table} ";
            $sql .= empty($this->where) ? "" : "WHERE {$this->where} ";
            $sql .= empty($this->order) ? "" : "ORDER BY {$this->order} ";
            if($isfind)
                $sql .= "LIMIT 1";
            else
                $sql .= empty($this->limit) ? "" : "LIMIT {$this->limit} ";

            $this->sql = $sql;
        }
    }

    public function setCreateAndUpdate(bool $flag) {
        $this->hasCreateAndUpdate = $flag;
    }

    public function select($type = 'named',$is_statement = false ,$is_reuse = false){
        if(end($this->stark) != 'insert' || empty($this->stark)){
        $this->setSql();
        $sth = $type == 'named' ? $this->db->prepare($this->sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]) : $this->db->prepare($this->sql);
        $this->exec($sth, $this->bind);
        $this->bind = [];
        $this->sql  = $is_reuse ? $this->sql : "";
        
        return $is_statement ? $sth : $sth->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $sql = "INSERT INTO {$this->table} ". $this->insert;
            echo $sql;
            $this->db->exec($sql);
        }
    }

    public function find($type = 'named', $is_reuse = false){
        $this->limit = "";
        $this->limit(1);
        $this->setSql();
        $sth = $type == 'named' ? $this->db->prepare($this->sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]) : $this->db->prepare($this->sql);
        $res = $this->exec($sth, $this->bind)->fetchAll(PDO::FETCH_ASSOC);
        $this->bind = [];
        $this->sql  = $is_reuse ? $this->sql : "";

        return $res;
    }

    public function insert($values) {
        $this->stark[] = 'insert';
        $this->insert = "";
        $fields = ['createAt','updateAt'];
        $vals   = [];
        if($flag = $this->isRelationArray($values)){
            foreach($values as $field => $value) {
                $fields[] = $field;
                $vals[]   = "'$value'";
            }
        }else{
            foreach($values as $value) {
                $vals[] = "'$value'";
            }
        }
        if($this->hasCreateAndUpdate){
            $date = new date("Y-m-d H:i:s");
            array_unshift($vals, $date);
            array_unshift($vals, $date);
        }else{
            array_shift($fields);
            array_shift($fields);
        }
        $vals = "(" . implode(',',$vals) .")";
        $fields = "(" . implode(',',$fields) .")";

        $this->insert = $flag ? $fields . "VALUES" . $vals : $vals;

        return $this;
    }
    //TODO 事务
    public function beginTransaction() {}

    public function commit(){}

    public function table($table) {
        $this->table = "`$table`";

        return $this;
    }

    //键值对表示使用别名
    public function field($fields) {
        if(is_array($fields)) {
            $isAssoc = array_keys($arr) !== range(0, count($arr) - 1);
            if($isAssoc) {
                foreach ($fields as $field => $alisa) 
                    $this->field .= ", $field as $alias";
            }else {
                foreach($fields as $field) 
                    $this->field .= ", $field";      
            }
        }else {
            $this->field .= ", $fields";
        }

        if(!empty($this->field)) 
            $this->field = substr($this->field, 1);

        return $this;
    }

    public function where($where){
        if(is_array($where)) {
            foreach ($where as $key => $val) {
                if(is_array($val)) {
                    $val = $val[0] . "\'$val[1]\'";
                }
                $this->where .= (" and " . $key . $val);
            }
        }else{
            $this->where .= " and $where";
        }

        return $this;
    }

    public function order($order){
        if(is_array($order)) {
            foreach($order as $key => $val) {
                if(empty($val)) {
                    $val = ' asc ';
                }
                $this->order .= ($key . $val);
            }
        } else {
            $this->order .= ($order . ' ');
        }

        return $this;
    }

    public function limit($start, $end = 0) {
        $limit  = $start;
        $offset = $end - $start + 1;

        $this->limit = $end == 0 ? " $start " : " $start, $offset ";

        return $this; 
    }

    private function exec($sth, $params = []){
        if(empty($params)){
            $isSuccess = $sth->execute();
        }else{
            $isSuccess = $sth->execute($params);
        }

        if($isSuccess) 
            return $sth;
        else {
            throw new PDOException($this->db->errorInfo());
            exit;
        }
    }

    private function isRelationArray(array $arr) {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function close(){
        $this->db = null;
    }

    public function __destruct(){
        $this->close();
    }
}