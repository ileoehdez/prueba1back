<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'db_config.php';

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->tarea) &&
    !empty($data->tipo) &&
    !empty($data->prioridad) &&
    !empty($data->fecha_inicio) &&
    !empty($data->descripcion) &&
    !empty($data->estado)) {
  $tarea = $data->tarea;
  $tipo = $data->tipo;
  $prioridad = $data->prioridad;
  $fecha_inicio = $data->fecha_inicio;
  $descripcion = $data->descripcion;
  $estado = $data->estado;

  $sql = "INSERT INTO tareas (tarea, tipo, prioridad, fecha_inicio, descripcion, estado) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $tarea, $tipo, $prioridad, $fecha_inicio, $descripcion, $estado);

  if ($stmt->execute()) {
    echo json_encode(array("mensaje" => "Tarea creada exitosamente.", "insertId" => $conn->insert_id));
  } else {
    echo json_encode(array("mensaje" => "No se pudo crear la tarea."));
  }
} else {
  echo json_encode(array("mensaje" => "Datos incompletos."));
}

$stmt->close();
$conn->close();
?>