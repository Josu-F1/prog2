<?php
$hostname = "localhost";
$username = "root";
$password = "josu12345";
$database = "programa3";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("error en la conexion a la base de datos" . $conn->connect_error);
}
//echo "conexion exitosa";
