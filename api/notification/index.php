<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once '../../api/user/user.php';
require '../../include/dbsol/conn.php';
require '../../vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/ussd/api/notification');
$db = new DBConn();
$pdo = $db->connectToDB();



$app->post('/', function(Request $request, Response $response) use($pdo) {
    $sessionId = $_POST['sessionId'];
    $serviceCode = $_POST['serviceCode'];
    $networkCode = $_POST['networkCode'];
    $phone = $_POST['phoneNumber'];
    $status = $_POST['status'];
    $durationInMillis  = $_POST['durationInMillis'];
    $date = $_POST['date'];

    
    $user = new User($phone);
    
    try {
        $user_id = $user->readUserId($pdo);
        print_r($user_id); exit;

        $stmt = $pdo->prepare("INSERT INTO notifications (sessionId, serviceCode, networkCode, phoneNumber, status, durationInMillis, date) values (?,?,?,?,?,?,?)");
        $stmt->execute([$sessionId, $serviceCode, $networkCode, $phone, $status, $durationInMillis, $date]);
        $stmt = null;
        $response->getBody()->write(json_encode($status));
        return $response->withHeader('Content-Type', 'application/json');

    } catch(PDOException $e){
        $response->getBody()->write(json_encode('An error had occurred.' . $e));
        return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->run();
?>