<?php
session_start();

//connection creation with my sql
$conn=mysql_connect("192.168.10.170","root","acer-1234") or die("Database Connection Failed");

//Database selection where operation have to be performed.

$selectdb=mysql_select_db("Abhishek_users") or die("Database could not be selected");


?>
