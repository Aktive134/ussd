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
            $ttype = $this->getTType();
            $amount = $this->getAmount();
            $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
            try{
                $pdo->beginTransaction();
                $stmtT = $pdo->prepare("INSERT INTO transactions ( amount, uid, ruid, ttype ) VALUES (?,?,?,?)");
                $stmtU = $pdo->prepare("UPDATE users SET balance=? WHERE uid=?");

                $stmtT->execute([$amount, $uid, $ruid, $ttype]);
                $stmtU->execute([$newSenderBalance, $uid]);
                $stmtU->execute([$newReceiverBalance, $ruid]);

                $pdo->commit();
                return true;

            } catch(PDOException $e) {
                $pdo->rollBack();
                return 'An Error Occured while processing your request';
            }
        }

        public function withdrawMoney($pdo, $uid, $aid, $newWithBalance) {
            $ttype = $this->getTType();
            $amount = $this->getAmount();
            $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);

            try {
                $pdo->beginTransaction();
                $stmtT = $pdo->prepare("INSERT INTO transactions ( amount, uid, aid, ttype ) VALUES (?,?,?,?)");
                $stmtU = $pdo->prepare("UPDATE users SET balance=? WHERE uid=?");

                $stmtT->execute([$amount, $uid, $aid, $ttype]);
                $stmtU->execute([$newWithBalance, $uid]);
                $pdo->commit();
                return true; 

            } catch (PDOException $e) {
                $pdo->rollBack();
                return 'An Error Occured while processing your request';
            }
        }

}

?>