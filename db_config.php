<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prueba1";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}
?>