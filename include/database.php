<?php
    class ConnectDB {
        public $pdo;

        public function __construct()
        {
            $this->pdo = new PDO ("mysql:host=127.0.0.1; dbname=calc", "root", "root");
        } 

        public function SelectTable($table, $param)
        {
            if(!empty($param)) {
                $sql_where = '';
                foreach($param as $key => $value){
                    $sql_where .= $key . '=:' . $key . ' AND ';
                }
                $sql_where = rtrim($sql_where, 'AND ');

                $sql = "SELECT * FROM $table WHERE $sql_where";
                $statement = $this->pdo->prepare($sql);
                $statement->execute($param);
            } else {
                $sql = "SELECT * FROM $table";
                $statement = $this->pdo->prepare($sql);
                $statement->execute();
            } 

            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public function InsertTable($table, $param){
            $sql_key = ''; 
            $sql_values = '';
            foreach($param as $key => $value){
                $sql_key .= $key . ', ';
            }
            foreach($param as $key => $value){
                $sql_values .= ':' . $key . ', ';
            }
            $sql_key = rtrim($sql_key, ', ');
            $sql_values = rtrim($sql_values, ', ');

            $sql = "INSERT INTO $table ($sql_key) VALUES($sql_values)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute($param);
        }
    }   

?>    
