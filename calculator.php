<?php
    session_start();

    require_once "include/database.php";
    require_once "include/function.php";

    $_SESSION['i'] = 1;
    function BtnPressed($key){
        switch($key){
            case '↺':
                if ($_SESSION['FormHistory_display'] == 'none'){
                    $_SESSION['FormHistory_display'] = 'block';
                } else {
                    $_SESSION['FormHistory_display'] = 'none';            
                }
                break;
            case '√':
                $_SESSION['first_action'] = "√";
                calc();
                break;
            case '¹/x':
                $_SESSION['first_action'] = "¹/x";
                calc();
                break;
            case 'x²':
                $_SESSION['first_action'] = "x²";
                calc();
                break;
            case '+':
                if($_SESSION['action'] == ''){
                    $_SESSION['action'] = "+";
                    $_SESSION['is_float'] = FALSE;  
                } else {
                    calc();
                }       
                break;
            case '-':
                if($_SESSION['action'] == ''){
                    $_SESSION['action'] = "-"; 
                    $_SESSION['is_float'] = FALSE;
                } else {
                    calc();
                }   
                break;    
            case '/':
                if($_SESSION['action'] == ''){
                    $_SESSION['action'] = "/"; 
                    $_SESSION['is_float'] = FALSE;
                } else {
                    calc();
                }       
                break;
            case '*':
                if($_SESSION['action'] == ''){
                    $_SESSION['action'] = "*";
                    $_SESSION['is_float'] = FALSE;   
                } else {
                    calc();
                }       
                break;     
            case 'C':
                $_SESSION = array();
                break;    
            case '.':
                if ($_SESSION['is_float'] != TRUE ){
                    $_SESSION['is_float'] = TRUE;
                    $valuePressed = '.';
                }
                break;            
            case '=':
                calc();
                break;
            case 0:
                $valuePressed = '0';
                break;    
            case 1:
                $valuePressed = '1';
                break;
            case 2:
                $valuePressed = '2';
                break;
            case 3:
                $valuePressed = '3';
                break;
            case 4:
                $valuePressed = '4';
                break;
            case 5:
                $valuePressed = '5';
                break;    
            case 6:
                $valuePressed = '6';
                break;    
            case 7:
                $valuePressed = '7';
                break;    
            case 8:
                $valuePressed = '8';
                break;
            case 9:
                $valuePressed = '9';
                break;                     
        }
        if($_SESSION['action']){
            $_SESSION['i']++;       
        }
        $_SESSION['x'. $_SESSION['i']] = $_SESSION['x'. $_SESSION['i']].$valuePressed;
    }

    function calc(){
        $operation = '';
        if (is_numeric($_SESSION['x1']) && is_numeric($_SESSION['x2'])) {
            $operation = $_SESSION['x1']. $_SESSION['action']. $_SESSION['x2'];
            if ($_SESSION['action'] == '+'){
                $_SESSION['x1'] =  $_SESSION['x1'] + $_SESSION['x2'];    
            }
            if ($_SESSION['action'] == '-'){
                $_SESSION['x1'] =  $_SESSION['x1'] - $_SESSION['x2'];  
            }  
            if ($_SESSION['action'] == '*'){
                $_SESSION['x1'] =  $_SESSION['x1'] * $_SESSION['x2'];  
            }  
            if ($_SESSION['action'] == '/'){
                if($_SESSION['x2'] == 0){
                    $_SESSION['x1'] = "Деление на ноль невозможно";
                } else {
                    $_SESSION['x1'] =  $_SESSION['x1'] / $_SESSION['x2'];
                }  
            }
            $operation .= ' = '. $_SESSION['x1'];
        } elseif (empty($_SESSION['first_action'])) {
            $_SESSION['x1'] = 0;
        }    

        if ($_SESSION['first_action'] == '√' && is_numeric($_SESSION['x1'])) {
            $operation = '√'. $_SESSION['x1'];
            $_SESSION['x1'] = bcsqrt($_SESSION['x1'], 2);  
            $operation .= ' = '. $_SESSION['x1'];
        }
        if ($_SESSION['first_action'] == '¹/x') {
            if($_SESSION['x1'] == 0 || $_SESSION['x1'] == ''){
                $_SESSION['x1'] = "Деление на ноль невозможно";
            } else {
                $operation = '¹/'. $_SESSION['x1'];
                $_SESSION['x1'] = 1/($_SESSION['x1']);
                $operation .= ' = '. $_SESSION['x1'];
            }      
        }
        if ($_SESSION['first_action'] == 'x²' && is_numeric($_SESSION['x1'])) {
            $operation = $_SESSION['x1']. '²';
            $_SESSION['x1'] = $_SESSION['x1'] * $_SESSION['x1'];  
            $operation .= ' = '. $_SESSION['x1'];
        }

        $_SESSION['first_action'] = '';
        $_SESSION['action'] = '';
        $_SESSION['x2'] = '';  

        // добавить в таблицу Истории операций
        if (!empty($operation)) {
            $db = new ConnectDB;
            $OperationHistory = new OperationHistory($db);
            $InsertOperationHistory = $OperationHistory->InsertOperationHistory('operations_history', session_id(), $operation);
        }    
    }

    if($_POST){
        if($_SESSION['x1'] == "Деление на ноль невозможно"){
            $_SESSION['x1'] = '';  
        } 
        BtnPressed(array_shift($_POST));
    }
    
    if($_GET){  
            $_SESSION['date'] = $_GET["calendar"];
            $_SESSION['sessionOption'] = $_GET['sessionOption'];   
            switch ($_SESSION['sessionOption']) {
                case 'Все':
                    $_SESSION['session'] = null;
                break;
                case 'Текущая сессия':
                    $_SESSION['session'] = session_id();
                break;
            }
            $db = new ConnectDB;
            $OperationHistory = new OperationHistory($db);  
            $SelectOperationHistory = $OperationHistory->SelectOperationHistory('operations_history',$_SESSION['session'],$_SESSION['date']);
            $_SESSION['SelectOperationHistory'] = $SelectOperationHistory;
    }
    
?>