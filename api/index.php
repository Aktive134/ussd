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
    $text = $menu->middleware($text, $sessionId, $pdo);
    $util = new Util();
    $message = "";

    try {
        if ($text == '' && $user->isUserRegistered($pdo) == true) {
            // User is registered and string is empty
            $name = $user->readName($pdo);
            $message = "CON " . $menu->mainMenuRegistered($name);
    
        } elseif ($text == '' && $user->isUserRegistered($pdo) == false) {
            // User is unregistered and string is empty
            $message = $menu->mainMenuUnRegistered();
           
        } elseif ($text !== '' && $user->isUserRegistered($pdo) == false) {
            // User is unregistered and string is not empty
            $textArray = explode('*', $text);
            switch ($textArray[0]) {
                case 1:
                    $message = $menu->registerMenu($textArray, $phone, $pdo);
                    break;
                case 2:
                    $message = $menu->subMenuUnRegisteredTwo();
                    break;
                default:
                    $message = 'END Invalid choice. Please try again. Thanks for using Bivety Bank';
            }

        } elseif ($text !== '' && $user->isUserRegistered($pdo) == true) {
            // User is registered and string is not empty
            $textArray = explode('*', $text);
            $name = $user->readName($pdo);

            switch ($textArray[0]) {
                case 1:
                    $message = $menu->sendMoneyMenu($textArray, $user, $pdo, $sessionId);
                    break;
                case 2:
                    $message = $menu->withdrawMoneyMenu($textArray, $user, $pdo);
                    break;
                case 3:
                    $message = $menu->checkBalanceMenu($textArray, $user, $pdo);
                    break;  
                default:
                    $ussdLevel = count($textArray) - 1;
                    $menu->persistInvalidEntry($sessionId, $ussdLevel, $pdo);
                    $message = "CON Invalid choice.\n" . $menu->mainMenuRegistered($name);
                    break;
            } 
           
        } else {
            $response->getBody()->write('An error had occurred.');
			return $response->withHeader('Content-Type', 'text/plain');
        }
            $response->getBody()->write($message);
            return $response->withHeader('Content-Type', 'text/plain');

    } catch (PDOException $e) {
            $response->getBody()->write(json_encode('An error had occurred.' . $e));
            return $response->withHeader('Content-Type', 'application/json');
    }
});

$app->run();
