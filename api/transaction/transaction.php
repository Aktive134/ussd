<?php

    class Transaction {
        protected $amount;
        protected $ttype;

        function __construct($amount, $ttype) 
        {
            $this->amount = $amount;
            $this->ttype = $ttype;
        }

        public function getAmount() {
            return $this->amount;
        }

        public function getTType() {
            return $this->ttype;
        }

        public function sendMoney($pdo, $uid, $ruid, $newSenderBalance, $newReceiverBalance) {
            $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
            try{
                $pdo->beginTransaction();
                $stmtT = $pdo->prepare("INSERT INTO transactions ( amount, uid, ruid, ttype ) VALUES (?,?,?,?)");
                $stmtU = $pdo->prepare("UPDATE users SET balance=? WHERE uid=?");

                $stmtT->execute([$this->getAmount(), $uid, $ruid, $this->getTType()]);
                $stmtU->execute([$newSenderBalance, $uid]);
                $stmtU->execute([$newReceiverBalance, $ruid]);

                $pdo->commit();
                return true;

            } catch(PDOException $e) {
                $pdo->rollBack();
                return 'An Error Occured while processing your request';
            }
        }

        public function withdrawMoney($pdo, $uid, $aid, $newSenderBalance) {

        }



    }




?>