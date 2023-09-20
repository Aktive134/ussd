<?php
    require_once '../../../include/utlites/util.php';
    

    class Menu {
        protected $text;
        protected $sessionId;

        function __construct() {}

        public function mainMenuRegistered(){
            $message = "CON Welcome to Bivety Bank. Would you like to: " .
            "\n1. Send Money" .
            "\n2. Withdraw Money" .
            "\n3. Add to Wallet";

            echo $message;
        }
        public function mainMenuUnRegistered(){
            $message = "CON Welcome to Bivety Bank .Unfortunately, we can't see your number in our system. Would you like to register? " .
			"\n1. Yes" .
			"\n2. No";
            echo $message;
        }

        public function subMenuUnRegisteredOne(){
            $message =  "END Your number has been recorded, we would get back to you";
            echo $message;
        }
        
        public function subMenuUnRegisteredTwo(){
            $message =  "END Thanks for using Bivety Bank USSD.";
            echo $message;
        }






    }

?>