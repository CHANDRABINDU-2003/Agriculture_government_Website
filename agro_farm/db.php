<?php
$host = 'sql104.infinityfree.com';
$user = 'if0_39322065';
$pass = 'y7dZbqQGEiXMY';
$dbname = 'if0_39322065_chandraCRUD';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
