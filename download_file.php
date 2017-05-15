<?php

include "db.php";
if (!isset($_SESSION['role']) && !isset($_COOKIE['role'])) {
    header('location:login.php');
}

$image = $_GET['image'];
if (file_exists($image)) {
    header('Content-Description: File Transfer');

    header('Content-Disposition: attachment; filename=' . basename($image));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($image));
    readfile($image);
    exit;
}


