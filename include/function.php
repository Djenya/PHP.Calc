<?php
    require_once "include/database.php";

    class OperationHistory {
        public $db;

        public function __construct(ConnectDB $db)
        {
            $this->db = $db;
        }

        public function SelectOperationHistory($table, $session, $date)
        {  
            $param = array();
            if ($session != NULL) {$param += ['Session' => $session];}
            if ($date != NULL) {$param += ['Date' => $date];} 

            $result = $this->db->SelectTable($table, $param);  
            return $result;
        }

        function InsertOperationHistory($table, $session, $operation){
            $param = [
                'Session' => $session,
                'Date' => date('Y/m/d'),
                'Operations' => $operation
            ];

            $result = $this->db->InsertTable($table, $param); 
            if ($result) {
                return TRUE;
            }
        }
    }

?>