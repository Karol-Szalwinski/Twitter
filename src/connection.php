<?php
session_start();
$servername = 'localhost';
$username = 'root';
$password ='CodersLab';
$basename = 'Twitter';

$conn = new mysqli($servername, $username, $password, $basename);
if($conn->connect_error) {
    die("Connection to database failed: $conn->connect_error");  
}

