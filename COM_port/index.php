<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Moj</title>
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

	echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">
	Port <select name="comm">';
	for($i=0;$i<$count;$i++) {
		echo '<option value="'.$comm_list[$i].'">'.$comm_list[$i].'</option>';	
	}
	echo '</select> Baud Rate <select name="baud">';
	for($i=0;$i<$count2;$i++) {
		echo '<option value="'.$baud_list[$i].'">'.$baud_list[$i].'</option>';	
	}
	echo '</select> <input type="submit" value="Select" />
	</form>
	<hr />';
        
        // First we must specify the device. This works on both linux and windows (if
        // your linux serial device is /dev/ttyS0 for COM1, etc)
        

        if($_POST['comm']=='None') {
		echo 'There\'s no active comm-port that can be used. Please check the connection<br />
		(Right Click on My Computer->Select Properties->Hardware->Device Manager->Ports (COM & LPT))<br />
		If there is a comm-port is active, make sure that it is not being used';
	} 
        else 
        {
            if(isset($_POST['comm'])) {
            $serial->deviceSet($_POST['comm']);
        $serial->confBaudRate(2400);
        $serial->confParity("none");
        $serial->confCharacterLength(8);
        $serial->confStopBits(1);
        $serial->confFlowControl("none");

        // Then we need to open it
        $serial->deviceOpen();

        // To write into
        $serial->sendMessage("Hello!");

        // Or to read from
        $read = $serial->readPort();

        // If you want to change the configuration, the device must be closed
       $serial->deviceClose();
            }
 else {
                echo 'Error';
 }
        
	}
        // We can change the baud rate, parity, length, stop bits, flow control
//        $serial->confBaudRate(2400);
//        $serial->confParity("none");
//        $serial->confCharacterLength(8);
//        $serial->confStopBits(1);
//        $serial->confFlowControl("none");
//
//        // Then we need to open it
//        $serial->deviceOpen();
//
//        // To write into
//        $serial->sendMessage("Hello!");
//
//        // Or to read from
//        $read = $serial->readPort();
//
//        // If you want to change the configuration, the device must be closed
//       $serial->deviceClose();

        // We can change the baud rate
        //$serial->confBaudRate(2400);
        // put your code here
        ?>
        
        <?php
//        
//        include('check_active_comm_port.class.php');
//
//if(isset($_POST['comm'])) {
//
//	if($_POST['comm']=='None') {
//		echo 'There\'s no active comm-port that can be used. Please check the connection<br />
//		(Right Click on My Computer->Select Properties->Hardware->Device Manager->Ports (COM & LPT))<br />
//		If there is a comm-port is active, make sure that it is not being used';
//	} 
//        else 
//        {
//		echo 'Port = '.$_POST['comm'].'<br /> Baud Rate = '.$_POST['baud'];
//	}
//
//
//} else {
//
//	$com = new windows_comm_port();
//	$comm_list = $com->comm_list();
//	$baud_list = $com->baud_list();
//
//	$count = count($comm_list);
//	$count2 = count($baud_list);
//
//	echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">
//	Port <select name="comm">';
//	for($i=0;$i<$count;$i++) {
//		echo '<option value="'.$comm_list[$i].'">'.$comm_list[$i].'</option>';	
//	}
//	echo '</select> Baud Rate <select name="baud">';
//	for($i=0;$i<$count2;$i++) {
//		echo '<option value="'.$baud_list[$i].'">'.$baud_list[$i].'</option>';	
//	}
//	echo '</select> <input type="submit" value="Select" />
//	</form>
//	<hr />';
//
//}
//        
//        ?>
    </body>
</html>
