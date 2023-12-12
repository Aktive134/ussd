<?php

    class Agent {
        protected $agentNumber;
        protected $name;

        function __construct($agentNumber) {
            $this->agentNumber = $agentNumber;
        }

        public function getAgentNumber() {
            return $this->agentNumber;
        }

        public function setAgentName() {
            $this->name = $name;
        }

        public function getAgentName() {
            return $this->name;
        }

        public function readAgentName($pdo) {
            $stmt = $pdo->prepare("SELECT name FROM agents WHERE agentNumber=?");
            $stmt->execute([$this->getAgentNumber()]);
            $row = $stmt->fetch();

            if($row == null) {
                return false;
            } else {
                return $row['name'];
            }

        }

        public function readAgentId ($pdo) {
            $stmt = $pdo->prepare("SELECT aid FROM agents WHERE agentNumber=?");
            $stmt->execute([$this->getAgentNumber()]);
            $row = $stmt->fetch();

            if($row == null) {
                return false;
            } else {
                return $row['aid'];
            }
        }
    }


?>