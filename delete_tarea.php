<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prueba1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(array("message" => "Error de conexión a la base de datos: " . $conn->connect_error));
    exit();
}

$id = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} 
else {
    $data = json_decode(file_get_contents("php://input"));
    if (isset($data->id)) {
        $id = $data->id;
    }
}

if (empty($id)) {
    http_response_code(400);
    echo json_encode(array("message" => "ID de tarea no proporcionado."));
    exit();
}

$sql = "DELETE FROM tareas WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(array("message" => "Tarea eliminada exitosamente."));
        } else {
            http_response_code(404); 
            echo json_encode(array("message" => "No se encontró la tarea con el ID proporcionado."));
        }
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Error al eliminar la tarea: " . $stmt->error));
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(array("message" => "Error en la preparación de la consulta: " . $conn->error));
}

// Cierra la conexión a la base de datos
$conn->close();
?>