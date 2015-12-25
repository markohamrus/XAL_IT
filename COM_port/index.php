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
function Test()
{
    <?php
        $datei = fopen("test.txt","w") or exit ("Unable to open file!"); 
        fwrite ($datei , "Hello World");
        fclose ($datei);
    ?>
}
</script>
    </head>
    <body>
        <h1>Hello</h1>
        <?php
        include "php_serial.php";
        include('check_active_comm_port.class.php');
        // Let's start the class
        $serial = new phpSerial;

        //Get the port and baud rate
        $com = new windows_comm_port();
        $comm_list = $com->comm_list();
        $baud_list = $com->baud_list();

        $count = count($comm_list);
        $count2 = count($baud_list);
        
        $data;

        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
	Port <select name="comm">';
        for ($i = 0; $i < $count; $i++) {
            echo '<option value="' . $comm_list[$i] . '">' . $comm_list[$i] . '</option>';
        }
        echo '</select> Baud Rate <select name="baud">';
        for ($i = 0; $i < $count2; $i++) {
            echo '<option value="' . $baud_list[$i] . '">' . $baud_list[$i] . '</option>';
        }
        echo '</select> <input type="submit" onClick="Test()" value="Odpri" />
        <hr/>            
	</form>
	<hr />';
        
        // First we must specify the device. This works on both linux and windows (if
        // your linux serial device is /dev/ttyS0 for COM1, etc)

        //Z klikom na gumb "Odpri" odpremo COM port na določenih vratih z določeno "baud rate"
        //TO-DO dodaj še ostale parametre kot so: stop bit, pariteto itd...
        if ($_POST['comm'] == 'None') {
            echo 'There\'s no active comm-port that can be used. Please check the connection<br />
		(Right Click on My Computer->Select Properties->Hardware->Device Manager->Ports (COM & LPT))<br />
		If there is a comm-port is active, make sure that it is not being used';
        } else {
            if (isset($_POST['comm'])) {
                $serial->deviceSet($_POST['comm']);
                $serial->confBaudRate(2400);
                $serial->confParity("none");
                $serial->confCharacterLength(8);
                $serial->confStopBits(1);
                $serial->confFlowControl("none");

                // Then we need to open it
                $serial->deviceOpen();

                // To write into
                //$serial->sendMessage("Hello!");

                //TO-DO podatki se nalagajo različno dolgo, glede na volumen. Poskrbeti za zanko, ki traja dokler se VSI podatki ne prenesejo.
                // Or to read from
                //$read = $serial->readPort();
                $bla = 1;
                while($serial->readPort())
                {
                    $f = fopen("textfile.txt", "w");
                    fwrite($f, $bla + 1);
                    
                }
                fclose($f);
                
                
                ?>
                <!--<textarea rows="4" cols="50" id="textarea1" name="data" ><?php echo $read ?></textarea>-->
                <?php
                
                //Odpremo datoteko za pisanje podatkov 
                //TO-DO datoteka mora biti zaščitena in read ONLY
                // Open the text file
                //$f = fopen("textfile.txt", "w");
                
                //fwrite($f, $read); 
                
                // Close the text file
                //fclose($f);

                //TO-DO verjetno se COM port "pokvari" ker je v vsakem primeru potrebno na koncu zapreti povezavo. 
                // If you want to change the configuration, the device must be closed
                $serial->deviceClose();
            } else {
                echo 'Prišlo je do napake!';
            }
        }
        ?>
    </body>
</html>
