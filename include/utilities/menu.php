<?php
    require_once '../include/utilities/util.php';
    require_once '../api/user/user.php';
        class Menu {
        protected $text;
        protected $sessionId;

        function __construct() {}

        public function mainMenuRegistered($name){
            $message = "Welcome " . $name . " to Bivety Bank. Would you like to: " .
            "\n1. Send Money" .
            "\n2. Withdraw Money" .
            "\n3. Check Balance";
            return $message;
        } 
        public function mainMenuUnRegistered(){
            $message = "CON Welcome to Bivety Bank .Unfortunately, we can't see your number in our system. Would you like to Register? " .
			"\n1. Yes" .
			"\n2. No";
            return $message;
        }
        
        public function subMenuUnRegisteredTwo(){
            $message =  "END Thanks for using Bivety Bank USSD.";
            return $message;
        }

        public function registerMenu($textArray, $phone, $pdo){
            $level = count($textArray);

            if($level == 1) {
                $message = 'CON Please enter your Full Name:';
                return $message;

            } else if ($level == 2) {
                $message = 'CON Please enter set your PIN:';
                return $message;

            } else if ($level == 3) {
                $message = 'CON Please re-enter your PIN';
                return $message; 

            } else if ($level == 4) {
                $name = $textArray[1];
                $pin = $textArray[2];
                $pin2 = $textArray[3];

                if ($pin != $pin2) {
                    $message = 'END Your pins do not match, Try again';
                    return $message;
                } else {
                    //register user in the database;
                    $user = new User($phone);
                    $user->setName($name);
                    $user->setPin($pin);
                    $user->setBalance(Util::$USER_INI_BALANCE);
                    $user->register($pdo);
                    //Send an SMS;
                    $message = 'END  Dear '. $name . ' You have successfully been registered';
                    return $message;
                }
            }
        }
        
        public function sendMoneyMenu ($textArray) {
            $level = count($textArray);
            switch ($level) {
                case 1:
                    $message =  'CON Enter Mobile Number of the receiver:';
                    return $message;
                    break;
                case 2:
                    $message =  'CON Enter the AMOUNT you want to send:';
                    return $message;
                    break;
                case 3:
                    $message =  'CON Enter your PIN:';
                    return $message;
                    break;
                case 4:
                    $message = 'CON You have requested to send the sum of ' . '$'. $textArray[2] . ' to ' . $textArray[1] .
                    "\n1. Confirm" .
                    "\n2. Cancel" .
                    "\n" . Util::$GO_BACK . " Back" .
                    "\n" . Util::$GO_TO_MAIN_MENU . " Main Menu";
                    return $message;
                    break;
                case 5:
                    if($textArray[4] == 1) {
                        //confirm transaction;
                        //send money + process;
                        //check if pin is correct
                        //check for available funds before transfer
                        $message = 'END Thank you, Your request is been processed';
                        return $message;

                    } else if ($textArray[4] == 2){
                        //cancel transaction;
                        $message = 'END Thank you for using our service';
                        return $message;

                    } else if ($textArray[4] == Util::$GO_BACK){
                        $message = 'END You have requested to go back one step';
                        return $message;

                    } else if ($textArray[4] == Util::$GO_TO_MAIN_MENU){
                        $message = 'END You have requested to go to main menu';
                        return $message;
                    }
                    break;

                default:
                    $message = 'END Invalid Entry, Please try again';
                    return $message;
                } 
        }
        public function withdrawMoneyMenu ($textArray) {
            $level = count($textArray);
            switch($level){
                case 1:
                    $message = 'CON Enter Agent Number:';
                    return $message;
                    break;
                case 2:
                    $message = 'CON Enter the AMOUNT:';
                    return $message;
                    break;
                case 3:
                    $message = 'CON Enter your PIN:';
                    return $message;
                    break;
                case 4:
                    $message = 'CON Withdraw ' . '$' . $textArray[2] . ' from Agent ' . $textArray[1] . ':' .
                    "\n1. Confirm" .
                    "\n2. Cancel" .
                    "\n" . Util::$GO_BACK . " Back" .
                    "\n" . Util::$GO_TO_MAIN_MENU . " Main Menu";
                    return $message;
                    break;
                case 5:
                    if ($textArray[4] == 1) {
                        $message = 'END You have request is been processed.';
                        return $message;

                    } else if ($textArray[4] == 2) {
                        $message = 'END You request has been canceled.';
                        return $message;
                        
                    } else if ($textArray[4] == Util::$GO_BACK) {
                        $message = 'END You have requested to go back one step';
                        return $message;

                    } else if ($textArray[4] == Util::$GO_TO_MAIN_MENU) {
                        $message = 'END You have requested to go to main menu';
                        return $message;
                    }
                    break;
                default:
                    $message = 'END Invalid Entry, Please try again';
                    return $message;
            }
        }
        public function checkBalanceMenu ($textArray) {
            $level = count($textArray);
            switch($level){
                case 1: 
                    $message = 'CON Please Enter Your PIN:';
                    return $message;
                    break;
                case 2:
                    $message = 'END We are processing your request, you will receive an SMS shortly';
                    return $message;
                    break;
                default:
                    $message = 'END Invalid Entry, Please try again';
                    return $message;
            }
        }

        public function middleware($text, $user, $sessionId, $pdo) {
            //remove entries for going back and going to the main menu;
           return $this->invalidEntry($this->goBack($this->goToMainMenu($text)), $user, $sessionId, $pdo);
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

        public function persistInvalidEntry($sessionId, $user, $ussdLevel, $pdo) {
            $stmt = $pdo->prepare("INSERT INTO ussdsession (sessionId, ussdLevel, uid) VALUES (?,?,?)");
            $stmt->execute([$sessionId, $ussdLevel, $user->readUserId($pdo)]);
            $stmt = null;
        }

        public function invalidEntry($ussdStr, $user, $sessionId, $pdo) {
            $stmt = $pdo->prepare("SELECT ussdLevel FROM ussdsession WHERE sessionId =? AND uid=?");
            $stmt->execute([$sessionId, $user->readUserId($pdo)]);
            $results = $stmt->fetchAll();

            if(count($results) == 0){
                return $ussdStr;
            }

            $responseArray = explode("*", $ussdStr);

            foreach($results as $value) {
                unset($responseArray[$value['ussdLevel']]);
            }

            $responseArray = array_values($responseArray); 
            return join("*", $responseArray);
        }

        public function addCountryCodeToPhone ($phone) {
            return Util::$COUNTRY_CODE .  substr($phone, 1);
        }
    }

?>