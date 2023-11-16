<?php
    class User {
        protected $name;
        protected $phone;
        protected $pin;
        protected $balance;

        function __construct($phone) {
            $this->phone = $phone;
        }

        //setter and getter
        public function setName($name){
            $this->name = $name;
        }

        public function setPin($pin){
            $this->pin = $pin;
        }

        public function setBalance($balance){
            $this->balance = $balance;
        }

        public function getName(){
            return $this->name;
        }

        public function getPhone(){
           return $this->phone;
        }

        public function getPin(){
           return $this->pin;
        }

        public function getBalance(){
           return $this->balance;
        }

        public function register ($pdo) {
            try{
                //hash the pin;
                $hashedPin = password_hash($this->getPin(), PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, pin, phone, balance) values (?,?,?,?)");
                $stmt->execute([$this->getName(),$hashedPin, $this->getPhone(), $this->getBalance()]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        public function isUserRegistered ($pdo) {
            try{
                $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
                $stmt->execute([$this->getPhone()]);
                if(count($stmt->fetchAll()) > 0){
                    return true;
                } else {
                    return false;
                }
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
        public function readName ($pdo) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
            $stmt->execute([$this->getPhone()]);
            $row = $stmt->fetch();
            return $row['name'];
        }
        public function readUserId ($pdo) {}
        public function correctPin ($pdo) {}
        public function checkBalance ($pdo) {}



    }
?>