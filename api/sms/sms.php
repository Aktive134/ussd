<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use AfricasTalking\SDK\AfricasTalking;

require_once '../include/utilities/util.php';
require '../vendor/autoload.php';

class SMS {
    protected $phone;
    protected $AT;

    function __construct ($phone) 
    {
        $this->phone = $phone;
        $this->AT  = new AfricasTalking(Util::$USERNAME,Util::$API_KEY);

    }

    public function getPhone(){
        return $this->phone;
    }

    public function sendSMS($message){
        //get SMS service;
        $sms = $this->AT->sms();

        $result = $sms->send([
            'to' => $this->getPhone(),
            'message' => $message,
            'from' => Util::$SMS_SHORTCODE
        ]);
        return $result;
    }
}


?>