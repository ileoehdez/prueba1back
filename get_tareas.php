<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'db_config.php';

$sql = "SELECT * FROM tareas";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $tareas = array();
  while ($row = $result->fetch_assoc()) {
    $tareas[] = $row;
  }
  echo json_encode($tareas);
} else {
  echo json_encode(array("mensaje" => "No se encontraron tareas."));
}

$conn->close();
?>