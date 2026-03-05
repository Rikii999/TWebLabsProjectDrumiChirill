<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "realestate";

$conn = new mysqli($host, $user, $password, $database);
if($conn->connect_error) die("Ошибка подключения: ".$conn->connect_error);
?>