<?php
    require_once '../include/utilities/util.php';
    require_once '../api/user/user.php';
        class Menu {
        protected $text;
        protected $sessionId;

        function __construct() {}

        public function mainMenuRegistered($name){
            $message = "CON Welcome " . $name . " to Bivety Bank. Would you like to: " .
            "\n1. Send Money" .
            "\n2. Withdraw Money" .
            "\n3. Check Balance";

            echo $message;
        }
        public function mainMenuUnRegistered(){
            $message = "CON Welcome to Bivety Bank .Unfortunately, we can't see your number in our system. Would you like to Register? " .
			"\n1. Yes" .
			"\n2. No";
            return $message;
            //echo $message;
            //return $message;
        }

        public function registerMenu($textArray, $phone, $pdo){
            $level = count($textArray);

            if($level == 1) {
                $message = 'CON Please enter your Full Name:';
                echo $message;

            } else if ($level == 2) {
                $message = 'CON Please enter set your PIN:';
                echo $message;

            } else if ($level == 3) {
                $message = 'CON Please re-enter your PIN';
                echo $message; 

            } else if ($level == 4) {
                $name = $textArray[1];
                $pin = $textArray[2];
                $pin2 = $textArray[3];

                if ($pin != $pin2) {
                    $message = 'END Your pins do not match, Try again';
                    echo $message;
                } else {
                    //register user in the database;
                    $user = new User($phone);
                    $user->setName($name);
                    $user->setPin($pin);
                    $user->setBalance(Util::$USER_INI_BALANCE);
                    $user->register($pdo);
                    //Send an SMS;
                    $message = 'END  Dear '. $name . ' You have successfully been registered';
                    echo $message;
                }
            }
        }
        
        public function subMenuUnRegisteredTwo(){
            $message =  "END Thanks for using Bivety Bank USSD.";
            echo $message;
        }

        public function sendMoneyMenu ($textArray) {
            $level = count($textArray);
            switch ($level) {
                case 1:
                    $message =  'CON Enter Mobile Number of the receiver:';
                    echo $message;
                    break;
                case 2:
                    $message =  'CON Enter the AMOUNT you want to send:';
                    echo $message;
                    break;
                case 3:
                    $message =  'CON Enter your PIN:';
                    echo $message;
                    break;
                case 4:
                    $message = 'CON You have requested to send the sum of ' . '$'. $textArray[2] . ' to ' . $textArray[1] .
                    "\n1. Confirm" .
                    "\n2. Cancel" .
                    "\n" . Util::$GO_BACK . " Back" .
                    "\n" . Util::$GO_TO_MAIN_MENU . " Main Menu";
                    echo $message;
                    break;
                case 5:
                    if($textArray[4] == 1) {
                        //confirm transaction;
                        //send money + process;
                        //check if pin is correct
                        //check for available funds before transfer
                        $message = 'END Thank you, Your request is been processed';
                        echo $message;

                    } else if ($textArray[4] == 2){
                        //cancel transaction;
                        $message = 'END Thank you for using our service';
                        echo $message;

                    } else if ($textArray[4] == Util::$GO_BACK){
                        $message = 'END You have requested to go back one step';
                        echo $message;

                    } else if ($textArray[4] == Util::$GO_TO_MAIN_MENU){
                        $message = 'END You have requested to go to main menu';
                        echo $message;
                    }
                    break;

                default:
                    $message = 'END Invalid Entry, Please try again';
                    echo $message;
                } 
        }
        public function withdrawMoneyMenu ($textArray) {
            $level = count($textArray);
            switch($level){
                case 1:
                    $message = 'CON Enter Agent Number:';
                    echo $message;
                    break;
                case 2:
                    $message = 'CON Enter the AMOUNT:';
                    echo $message;
                    break;
                case 3:
                    $message = 'CON Enter your PIN:';
                    echo $message;
                    break;
                case 4:
                    $message = 'CON Withdraw ' . '$' . $textArray[2] . ' from Agent ' . $textArray[1] . ':' .
                    "\n1. Confirm" .
                    "\n2. Cancel" .
                    "\n" . Util::$GO_BACK . " Back" .
                    "\n" . Util::$GO_TO_MAIN_MENU . " Main Menu";
                    echo $message;
                    break;
                case 5:
                    if ($textArray[4] == 1) {
                        $message = 'END You have request is been processed.';
                        echo $message;

                    } else if ($textArray[4] == 2) {
                        $message = 'END You request has been canceled.';
                        echo $message;
                        
                    } else if ($textArray[4] == Util::$GO_BACK) {
                        $message = 'END You have requested to go back one step';
                        echo $message;

                    } else if ($textArray[4] == Util::$GO_TO_MAIN_MENU) {
                        $message = 'END You have requested to go to main menu';
                        echo $message;
                    }
                    break;
                default:
                    $message = 'END Invalid Entry, Please try again';
                    echo $message;
            }
        }
        public function checkBalanceMenu ($textArray) {
            $level = count($textArray);
            switch($level){
                case 1: 
                    $message = 'CON Please Enter Your PIN:';
                    echo $message;
                    break;
                case 2:
                    $message = 'END We are processing your request, you will receive an SMS shortly';
                    echo $message;
                    break;
                default:
                    $message = 'END Invalid Entry, Please try again';
                    echo $message;
            }
        }

        public function middleware($text) {
            //remove entries for going back and going to the main menu;
           return $this->goBack($this->goToMainMenu($text));
        }

        public function goBack($text) {
            $explodedText = explode("*", $text);
            while(array_search(Util::$GO_BACK, $explodedText) != false) {
                $firstIndex = array_search(Util::$GO_BACK, $explodedText);
                array_splice($explodedText, $firstIndex - 1, 2);
            }
            return join("*", $explodedText); 
        }

        public function goToMainMenu($text) {
            $explodedText = explode("*", $text);
            while(array_search(Util::$GO_TO_MAIN_MENU, $explodedText) != false) {
                $firstIndex = array_search(Util::$GO_TO_MAIN_MENU, $explodedText);
                $explodedText = array_slice($explodedText, $firstIndex + 1);
            }
            return join("*", $explodedText);
        }
    }

?>