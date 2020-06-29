<?php
    session_start();
    
    require_once "include/database.php";
    require_once "include/function.php";
    require_once "calculator.php";
    
    echo "<head>";
        echo "<link  rel=\"stylesheet\" type=\"text/css\" href=\"style.css\"/>";
        echo "<title>Калькулятор</title>";
    echo "</head>";
    
        $buttons=['√','x²','¹/x','/',1,2,3,'*',4,5,6,'-',7,8,9,'+','C',0,'.','='];
        $display=$_SESSION['x1']. $_SESSION['action']. $_SESSION['x2'];
        
        echo "<div id=\"main\">";
            echo "<div id=\"divCalc\">";
                echo "<form method=\"POST\">";
                    echo "<table>";
                        echo "<tr>";
                            echo "<td class=\"tdRes\" colspan=\"3\">$display</td>";
                            echo "<td><button name=\"btnHistoryShowHide\" class=\"btnHistoryShowHide\" value=\"↺\"></button></td>";
                        echo "</tr>";
                        foreach(array_chunk($buttons,4) as $chunk){
                            echo "<tr>";
                                foreach($chunk as $button){
                                    echo "<td",(sizeof($chunk)!=4?" colspan=\"4\"":""),"><button name=\"pressed\" value=\"$button\">$button</button></td>";
                                }
                            echo "</tr>";
                        }
                    echo "</table>";
                    echo "<input type=\"hidden\" name=\"stored\" value=\"$display\">";
                echo "</form>";
            echo "</div>";    
            
            if(!isset($_SESSION['FormHistory_display'])) { $_SESSION['FormHistory_display'] = "none"; }    
            echo "<div id=\"divFormHistory\" style=\"display:". $_SESSION['FormHistory_display']. ";\">";
                echo "<form method=\"GET\">";
                    echo "<p>Выберите дату: <input type=\"date\" name=\"calendar\" value=".$_SESSION['date']."></p>";
                    echo "<p>Сессия: <select name=\"sessionOption\">";
                        $options = array('Все', 'Текущая сессия');
                        $output = '';
                        for( $i=0; $i<count($options); $i++ ) {
                            $output .= '<option ' 
                                . ( $_SESSION['sessionOption'] == $options[$i] ? 'selected="selected"' : '' ) . '>' 
                                . $options[$i] 
                                . '</option>';
                        }
                        echo $output;
                    echo "</select></p>";
                    echo "<input type=\"submit\" value=\"Применить\"></p>";
                    
                    if ($_SESSION['SelectOperationHistory']){
                        echo "<table>";
                            echo "<tr>";
                                echo "<th>Операция</th>";
                                echo "<th>Дата операции</th>";
                                echo "<th>Сессия</th>";
                            echo "</tr>";
                            foreach ($_SESSION['SelectOperationHistory'] as $key) {
                                echo "<tr>";
                                    echo "<td>".$key['Operations']."</td>";
                                    echo "<td>".$key['Date']."</td>";
                                    echo "<td>".$key['Session']."</td>";
                                echo "</tr>";
                            } 
                        echo "</table>";
                    }
                    
                echo "</form>";
            echo "</div>";     
        echo "</div>";          
    echo "</body>";
?>