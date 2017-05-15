<?php
include "db.php";

if (!isset($_SESSION['role']) && !isset($_COOKIE['role']))
{
    header('location:login.php');
}
    if(isset($_SESSION['role']) && $_SESSION['role']== "Admin" || isset($_COOKIE['role']) && $_COOKIE['role']== "Admin" )
        {
            
            $id = $_GET['id'];
        }
     if(isset($_SESSION['role']) && $_SESSION['role']== "user" || isset($_COOKIE['role']) && $_COOKIE['role']== "user")
        {
            header('Location: select_data.php');
        }
    
   
        
     
    
//Get method to retrieve id from the url 

if($_SESSION['role']== "Admin" && $id!= $_SESSION['id'] || $_COOKIE['role']== "Admin" && $id!= $_COOKIE['id'])
{
$image=$_GET['image'];

$data = mysql_query("DELETE FROM `registration` WHERE `id`='$id' ") or die(mysql_error());
unlink($image);
header('Location: select_data.php');
}
else
{
header('Location: select_data.php');
    
}