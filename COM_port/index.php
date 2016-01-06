<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>COM port</title>
        <script>
            function onFileSelected(event) {
            var selectedFile = event.target.files[0];
            var reader = new FileReader();

            var result = document.getElementById("result");

            reader.onload = function(event) {
              result.innerHTML = event.target.result;
            };

            reader.readAsText(selectedFile);
          }
            
        </script>
    </head>
    <body>
        <h1>Branje podatkov</h1>
        <?php
        include "php_serial.php";
        include('check_active_comm_port.class.php');
        
        ini_set('max_execution_time', 300);
        // Let's start the class
        $serial = new phpSerial;
        $odprto = false;

        //Get the port and baud rate
        $com = new windows_comm_port();
        $comm_list = $com->comm_list();
        $baud_list = $com->baud_list();

        $count = count($comm_list);
        $count2 = count($baud_list);

        $data;

        echo '<iframe name="frame" style="display:none;"></iframe>';

        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" target="frame">
	Port <select name="comm">';
        for ($i = 0; $i < $count; $i++) {
            echo '<option value="' . $comm_list[$i] . '">' . $comm_list[$i] . '</option>';
        }
        echo '</select> Baud Rate <select name="baud">';
        for ($i = 0; $i < $count2; $i++) {
            echo '<option value="' . $baud_list[$i] . '">' . $baud_list[$i] . '</option>';
        }
        echo '</select> <input type="submit" name="gumb" id="gumb_open"  value="Odpri" />
        <hr/>            
	</form>
	<hr />';
        ?>
        <input type="file" onchange="onFileSelected(event)">
        </br>
        <textarea cols="20" readonly="true" rows="20" id="result"></textarea>
        <button onClick="myFunction()" id="gumb_start">Start</button>
        <button onClick="my()" id="gumb_stop">Stop</button>
        <?php
       
        echo '<form action="' . $_SERVER['PHP_SELF'] . '"method="post" target="frame">
                                
                <input type="submit" name="gumb_start" value="Start scr"/>
                <input type="submit" name="gumb_stop" value="Stop src"/>
             </form>';

        
//        echo '<form action="' . $_SERVER['PHP_SELF'] . '"method="post" target="frame">
//                <input type="file" name="dat2"/>                
//                <input type="submit" name="file" value="Odpri"/>
//             </form>';
        // First we must specify the device. This works on both linux and windows (if
        // your linux serial device is /dev/ttyS0 for COM1, etc)
        //Z klikom na gumb "Odpri" odpremo COM port na določenih vratih z določeno "baud rate"
        //TO-DO dodaj še ostale parametre kot so: stop bit, pariteto itd...
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['comm'] == 'None') {
                echo 'There\'s no active comm-port that can be used. Please check the connection<br />
		(Right Click on My Computer->Select Properties->Hardware->Device Manager->Ports (COM & LPT))<br />
		If there is a comm-port is active, make sure that it is not being used';
            } else {
                if (isset($_POST['comm'])) {
                    $serial->deviceSet($_POST['comm']);
                    $serial->confBaudRate($_POST['baud']);
                    $serial->confParity("none");
                    $serial->confCharacterLength(7);
                    $serial->confStopBits(1);
                    $serial->confFlowControl("none");

                    // Then we need to open it
                    $serial->deviceOpen();
                    $odprto = true;
                    $podatki;
                    $cas = time();
                    $minute = $cas + (1.5 * 60);

                    $time = date('m-d-Y H:i:s', $cas);
                    $finish_time = date('m-d-Y H:i:s', $minute);
                    $tmp;
                    $f = fopen("textfile.txt", "w", "a") or die("Unable to open file!");
                    while ($time < $finish_time) {
                        $time = date('m-d-Y H:i:s', time());
                        //Pobiramo podatke iz serijskega porta
                        $podatki = fwrite($f, $serial->readPort());
                        $tmp = $tmp . $podatki;
                    }
                    $serial->deviceClose();
                    fclose($f);

                    echo '<script>alert("Podatki prebrani!")</script>';
                    echo '<script>
                            var el = document.getElementById("result");
                            el.innerHTML = "' . $tmp . '"
                            </script>';
                   
                    }
                    else if(isset($_POST['gumb_start']))
                    {   
//                        if (substr(php_uname(), 0, 7) == "Windows"){ 
//                        pclose(popen("start /B ". "serial.exe start", "r"));  
//                        } 
//                        else { 
//                            exec("serial.exe start" . " > /dev/null &");   
//                        }  
                        if (substr(php_uname(), 0, 7) == "Windows")
                        { 
                            
                            $serial->deviceSet("COM5");
                            $serial->confBaudRate("2400");
                            $serial->confParity("none");
                            $serial->confCharacterLength(7);
                            $serial->confStopBits(1);
                            $serial->confFlowControl("none");

                            // Then we need to open it
                            $serial->deviceOpen();
                    
                            $com = "COM5";
                            $baud = "2400";
                            popen("start /B C:\\xampp\\php\\php.exe script.php" . $serial, "r");  
        //                            sleep(5);
        //                            $pclose($p);
                                } 
                                else { 
                                    exec("C:\\xampp\\php\\php.exe script.php" . " > /dev/null &");   
                                } 
                        //exec("nohup php script.php &");
                    }
                    else if(isset($_POST['gumb_stop']))
                    {
                        //shell_exec("taskkill -F /im serial.exe");
                        shell_exec("taskkill -F /im php.exe");
                        $serial->deviceClose();
                    }
                    else {
                        echo 'Prišlo je do napake!';
                    }
                }
            }
            ?>
    </body>
</html>
