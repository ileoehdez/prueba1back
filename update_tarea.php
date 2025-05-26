<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prueba1";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(array("message" => "Error de conexión a la base de datos: " . $conn->connect_error));
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id) || empty($data->tarea) || !isset($data->tipo) || !isset($data->prioridad) || !isset($data->tipo) || !isset($data->estado)) {
    http_response_code(400);
    echo json_encode(array("message" => "Datos de tarea incompletos. Se requieren mas informacion."));
    exit();
}

$id = $data->id;
$tarea = $data->tarea;
$tipo = $data->tipo;
$prioridad = $data->prioridad;
$descripcion = $data->descripcion;
$estado = $data->estado;

$sql = "UPDATE tareas SET tarea = ?, tipo = ?, prioridad, descripcion = ?, estado = ? WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("sssssi", $tarea, $tipo, $prioridad, $descripcion, $estado, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(array("message" => "Tarea actualizada exitosamente."));
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontró la tarea con el ID proporcionado o no se realizaron cambios."));
        }
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Error al actualizar la tarea: " . $stmt->error));
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(array("message" => "Error en la consulta: " . $conn->error));
}

if($_POST["tareas"] == 'update_tarea'){

	$id_tarea = $_POST["id_tarea"];
	$tarea = $_POST["tarea"];
	$tipo = $_POST["tipo"];
	$prioridad = $_POST["prioridad"];
	$descripcion = $_POST["descripcion"];
    $estado = $_POST["estado"];

	$row = mysql_fetch_array(mysql_query("SELECT id_tarea FROM tareas WHERE id_tarea = '$id_tarea'"));

	if(!empty($row["id_tarea"])){
		mysql_query("UPDATE tareas SET tarea = '$tarea', tipo = '$tipo', prioridad = '$prioridad', descripcion = '$descripcion', estado ='$estado' WHERE id_tarea = '$id_tarea'");
	}
} else {
    http_response_code(500);
    echo json_encode(array("message" => "Error en la consulta: " . $conn->error));
}

$conn->close();
?>