<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");

function sendJsonResponse($statusCode, $body) {
    http_response_code($statusCode);
    echo json_encode($body, JSON_UNESCAPED_SLASHES);
    exit;
}

require_once __DIR__ . "/../Class/Database.php";
require_once __DIR__ . "/../Class/Pet.php";

$database = new Database();
$db = $database->getConnection();
$pet = new Pet($db);

$method = $_SERVER["REQUEST_METHOD"];

try {
    if ($method === "POST") {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->name) && !empty($data->breed) && isset($data->age) && !empty($data->city)) {
            $type = !empty($data->type) ? $data->type : 'Pet';
            $image = !empty($data->image) ? $data->image : '';
            $user_id = !empty($data->user_id) ? $data->user_id : null;

            if ($pet->createPet($data->name, $type, $data->breed, $data->age, $data->city, $image, $user_id)) {
                sendJsonResponse(200, array("success" => true, "message" => "Pet created successfully."));
            } else {
                sendJsonResponse(500, array("success" => false, "error" => "Unable to create pet."));
            }
        } else {
            sendJsonResponse(400, array("success" => false, "error" => "Incomplete data provided."));
        }
    } 
    else if ($method === "GET") {
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
        if ($user_id) {
            sendJsonResponse(200, array("success" => true, "data" => $pet->getByUser($user_id)));
        } else {
            sendJsonResponse(200, array("success" => true, "data" => []));
        }
    }
} catch (Throwable $exception) {
    sendJsonResponse(500, array("success" => false, "error" => "Internal error."));
}
?>