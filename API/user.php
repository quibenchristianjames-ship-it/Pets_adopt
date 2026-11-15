
<?php
// Enable CORS headers for all origins and methods
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS requests immediately
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");

function sendJsonResponse($statusCode, $body) {
    http_response_code($statusCode);
    echo json_encode($body, JSON_UNESCAPED_SLASHES);
    exit();
}

require_once __DIR__ . "/../Class/Database.php";
require_once __DIR__ . "/../Class/User.php";

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$method = $_SERVER["REQUEST_METHOD"];

try {
    if ($method === "POST") {
        $data = json_decode(file_get_contents("php://input"));
        $action = $data->action ?? 'login';

        if ($action === 'register') {
            if (!empty($data->name) && !empty($data->email) && !empty($data->password)) {
                $result = $user->register($data->name, $data->email, $data->password);
                sendJsonResponse($result['success'] ? 200 : 400, $result);
            } else {
                sendJsonResponse(400, ["success" => false, "error" => "Incomplete data provided."]);
            }
        } else {
            if (!empty($data->email) && !empty($data->password)) {
                $result = $user->login($data->email, $data->password);
                sendJsonResponse($result['success'] ? 200 : 401, $result);
            } else {
                sendJsonResponse(400, ["success" => false, "error" => "Incomplete data provided."]);
            }
        }
    } else {
        sendJsonResponse(405, ["success" => false, "error" => "Method not allowed."]);
    }
} catch (Throwable $exception) {
    sendJsonResponse(500, ["success" => false, "error" => "An internal server error occurred."]);
}
?>