<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require '../include/utilities/menu.php';
require_once '../include/utilities/util.php';
require '../include/dbsol/conn.php';
require '../vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/ussd/api');

$app->post('/', function (Request $request, Response $response) {
    // Read the variables sent via POST from our API
    $sessionId = $_POST['sessionId'];
    $serviceCode = $_POST['serviceCode'];
    $phone = $_POST['phoneNumber'];
    $text = $_POST['text'];

    $isRegistered = false;

    // Create an object instance of the class Menu
    $menu = new Menu();
    $text = $menu->middleware($text);
    $util = new Util();

    try {
        if ($text == '' && !$isRegistered) {
            // User is registered and string is empty
            $menu->mainMenuRegistered();

        } elseif ($text == '' && $isRegistered) {
            // User is unregistered and string is empty
            $menu->mainMenuUnRegistered();

        } elseif ($text !== '' && $isRegistered) {
            // User is unregistered and string is not empty
            $textArray = explode('*', $text);
            switch ($textArray[0]) {
                case 1:
                    $menu->registerMenu($textArray);
                    break;
                case 2:
                    $menu->subMenuUnRegisteredTwo();
                    break;
                default:
                    echo 'END Invalid choice. Please try again. Thanks for using Bivety Bank';
            }
        } elseif ($text !== '' && !$isRegistered) {
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
