<?php
    class ConectarInformix{
        protected $var2;

        
        protected $dbif;

        protected function Conexion(){
            $hostx='192.168.10.80';
            $db1x='tbsai';
            $serverx='online01'; //online01
            $portx='1531'; //'1531'
            
            try {
				$conectar = $this->dbif = new PDO("informix:host=$hostx; service=$portx; database=$db1x; server=$serverx; protocol=onsoctcp; client_locale=en_US.819; db_locale=en_US.819; EnableScrollableCursors=1", "tbsai01", "tbsai01");
				return $conectar;	
			} catch (Exception $e) {
				print "¡Error BD INFORMIX!: " . $e->getMessage() . "<br/>";
				die();	
			}
        }

        public function set_names(){	
			return $this->dbif->query("SET NAMES 'utf8'");
        }
    }
?>