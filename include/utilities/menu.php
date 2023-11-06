<?php
    require_once '../include/utilities/util.php';
        class Menu {
        protected $text;
        protected $sessionId;

        function __construct() {}

        public function mainMenuRegistered(){
            $message = "CON Welcome to Bivety Bank. Would you like to: " .
            "\n1. Send Money" .
            "\n2. Withdraw Money" .
            "\n3. Check Balance";

            echo $message;
        }
        public function mainMenuUnRegistered(){
            $message = "CON Welcome to Bivety Bank .Unfortunately, we can't see your number in our system. Would you like to Register? " .
			"\n1. Yes" .
			"\n2. No";
            echo $message;
        }

        public function registerMenu($textArray){
            $level = count($textArray);

            if($level == 1) {
                echo 'CON Please enter your Full Name:';

            } else if ($level == 2) {
                echo 'CON Please enter set your PIN:';

            } else if ($level == 3) {
                echo 'CON Please re-enter your PIN'; 

            } else if ($level == 4) {
                $name = $textArray[1];
                $pin = $textArray[2];
                $pin2 = $textArray[3];

                if ($pin != $pin2) {
                    echo 'END Your pins do not match, Try again';
                } else {
                    //register user in the database;
                    //Send an SMS;
                    echo 'END  Dear '. $name . ' You have successfully been registered';
                }
            }


            // $message =  "END Your number has been recorded, we would get back to you";
            // echo $message;
        }
        
        public function subMenuUnRegisteredTwo(){
            $message =  "END Thanks for using Bivety Bank USSD.";
            echo $message;
        }

        public function sendMoneyMenu () {}
        public function withdrawMoneyMenu () {}
        public function checkBalanceMenu () {}




    }

?>