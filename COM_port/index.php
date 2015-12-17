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
        
        // Let's start the class
        $serial = new phpSerial;

        // First we must specify the device. This works on both linux and windows (if
        // your linux serial device is /dev/ttyS0 for COM1, etc)
        $serial->deviceSet("COM1");

        // We can change the baud rate, parity, length, stop bits, flow control
        $serial->confBaudRate(2400);
        $serial->confParity("none");
        $serial->confCharacterLength(8);
        $serial->confStopBits(1);
        $serial->confFlowControl("none");

        // Then we need to open it
        $serial->deviceOpen();

        // To write into
        $serial->sendMessage("Hello !");

        // Or to read from
        $read = $serial->readPort();

        // If you want to change the configuration, the device must be closed
        $serial->deviceClose();

        // We can change the baud rate
        $serial->confBaudRate(2400);
        // put your code here
        ?>
    </body>
</html>
