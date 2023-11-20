<?php
    class Model{
        private $db;
        private static $instance = null;

        private function __construct(){
            include "credentials.php";
            $this->bd = new PDO($dsn, $login, $mdp);
            $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->bd->query("SET nameS 'utf8'");
        }
        
        public static function getModel(){
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        
    }
?>