<?php

Class connect{
    public $host = "localhost";
    public $user = "root";
    public $password = "";

    public $db = "test";
   public $charset = "utf8";

    public $pdo = "";

    public function __construct() {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $opt = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );
        $this->pdo = new PDO($dsn, $this->user, $this->password, $opt);
    }
}


Class db extends connect{

    /**
     * добавление записи в таблицу
     * @param array $data массив данных для сохранения
     * @return Boolen
     */
    public function add($data)
    {
        $fields = $this->set_fields($data);
        $sql = "INSERT INTO `{$this->name_table}` SET ".$fields;
        $stmt = $this->pdo->prepare( $sql );
        return $stmt->execute($data);
    }


    public function update($data, $where = "")
    {
        $fields = $this->set_fields($data);
        $sql = "UPDATE `{$this->name_table}` SET ".$fields;
        if(!empty($where)){
            $sql.= " WHERE ".$where;
        }
        $stmt = $this->pdo->prepare( $sql );
        return $stmt->execute($data);
    }

    /**

     * удаление  записи

     * @param $where - (если не пустой )

     * @return bool

     */
    public function delete($where = "") {
        //$fields = $this->set_fields($data);
        $sql = "DELETE  FROM `{$this->name_table}`  ";
        if (!empty($where)) {
            $sql.= " WHERE " . $where;
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }


    public function set_fields($items) {
        $str = array();
        if (empty($items))
            return '';
        foreach ($items as $key => $item) {
            $str[] = "`" . $key . "`=:" . $key;
        }
        return implode(',', $str);
    }


    /**

     * подсчет кол-ва записей

     * @return Integer

     */


    public function get_count( $where = array() )
    {

        $sql = "SELECT count(*) FROM {$this->name_table}";
        if( count( $where) > 0 ){
            $fields = $this->set_fields($where, " AND ");

            $sql .= " WHERE ".$fields;
        }

        $smtp = $this->pdo->prepare($sql);
        $smtp->execute($where);
        $result = $smtp->fetch( PDO::FETCH_NUM );

        return (int)$result[0];
    }


    public function get_all($order = "id asc")
    {
        $sql = "SELECT * FROM `{$this->name_table}` ORDER BY $order";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }



    public function get_one($id)
    {
        $sql = "SELECT * FROM {$this->name_table} WHERE id=:id";
        $stmt = $this->pdo->prepare( $sql );
        $stmt->execute(array("id" => $id));
        $result = $stmt->fetch();
        return $result;
    }





}





?>