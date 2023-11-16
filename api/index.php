<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require '../include/utilities/menu.php';
require_once '../include/utilities/util.php';
require_once '../api/user/user.php';
require '../include/dbsol/conn.php';
require '../vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/ussd/api');
$db = new DBConn();
$pdo = $db->connectToDB();
$nowtime = strtotime("now");



$app->post('/', function (Request $request, Response $response) use($pdo, $nowtime) {
    // Read the variables sent via POST from our API
    $sessionId = $_POST['sessionId'];
    $serviceCode = $_POST['serviceCode'];
    $phone = $_POST['phoneNumber'];
    $text = $_POST['text'];
    $user = new User($phone);
    $menu = new Menu();
    $text = $menu->middleware($text);
    $util = new Util();

    try {
        if ($text == '' && $user->isUserRegistered($pdo)) {
            // User is registered and string is empty
            $name = $user->readName($pdo);
            $menu->mainMenuRegistered($name);

        } elseif ($text == '' && !$user->isUserRegistered($pdo)) {
            // User is unregistered and string is empty
             $message = $menu->mainMenuUnRegistered();
             echo $message;
            //  //return $response->getBody()->write(json_encode($message));
            //  $response->getBody()->write(json_encode($message));
            //  return $response->withStatus(200);

        } elseif ($text !== '' && !$user->isUserRegistered($pdo)) {
            // User is unregistered and string is not empty
            $textArray = explode('*', $text);
            switch ($textArray[0]) {
                case 1:
                    $menu->registerMenu($textArray, $phone, $pdo);
                    break;
                case 2:
                    $menu->subMenuUnRegisteredTwo();
                    break;
                default:
                    echo 'END Invalid choice. Please try again. Thanks for using Bivety Bank';
            }
        } elseif ($text !== '' && $user->isUserRegistered($pdo)) {
            // User is registered and string is not empty
            $textArray = explode('*', $text);

            switch ($textArray[0]) {
                case 1:
                    $menu->sendMoneyMenu($textArray);
                    break;
                case 2:
                    $menu->withdrawMoneyMenu($textArray);
                    break;
                case 3:
                    $menu->checkBalanceMenu($textArray);
                    break;  
                default:
                    echo 'END Invalid choice. Please try again. Thanks for using Bivety Bank';
                    break;
            } 

        }
    } catch (PDOException $e) {
        $failed = [
            'status' => 'error',
            'message' => 'An error had occurred.' . $e,
        ];
        return json_encode($failed);
    }
});

$app->run();
