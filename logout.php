<?php
include 'db.php';
session_destroy();
setcookie("id",$row["id"] , time()-3600, "/");
setcookie("role", $row["role"], time()-3600, "/");
setcookie("firstname", $row["firstname"], time()-3600, "/");
setcookie("lastname", $row["lastname"], time()-3600, "/");
header('location:login.php');