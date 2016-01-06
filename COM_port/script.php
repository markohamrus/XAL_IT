<?php

//TO-DO argument: COM port? filename




include "php_serial.php";

$argument1 = $argv[1];

while(true)
{
    $f = fopen("textfile.txt", "w", "a") or die("Unable to open file!");
    $podatki = fwrite($f, $argument1->readPort());
    fclose($f);
}
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

