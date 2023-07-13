<?php
$host = "localhost";
$username = "root";
$pw = "";
$db_name = "dummy_db";

$conn = new mysqli($host, $username, $pw, $db_name);

if(!$conn){
    die("Database Connection Failed");
}