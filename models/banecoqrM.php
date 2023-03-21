<?php
    class banecoqr {
        private $user;
        private $password;
        private $aeskey;
        private $cta_encrypt;
        private $url;
        public $qrId;

        public function __construct($user,$password,$aeskey,$cta_encrypt,$url) {
           
            $this->user=$user;
            $this->password=$password;
            $this->aeskey=$aeskey;
            $this->cta_encrypt=$cta_encrypt;
            $this->url=$url;
            
        }

        public function cifrar_password()
        {
         
            $url="$this->url"."api/authentication/encrypt?text=".$this->password."&aesKey=".$this->aeskey."";
            $open= file_get_contents($url);
            $llave=str_replace('"',"",$open);
            return($llave);
        }

        public function autenticar($llave)
        {
         
            $url ="$this->url"."api/authentication/authenticate";
            $au = curl_init($url);
          
            //Json via POST
            $data = array(
                "userName" => "$this->user",
                "password" => $llave
            );
            
            $payload = json_encode($data);
            
            //print($payload);
            //attaau encoded JSON string to the POST fields
            curl_setopt($au, CURLOPT_POSTFIELDS, $payload);
            //set the content type to application/json
            curl_setopt($au, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            //return response instead of outputting
            curl_setopt($au, CURLOPT_RETURNTRANSFER, true);
            //execute the POST request
            $result = curl_exec($au);
            //echo "<br>";
            //echo "<br>";
            //print "Token Generado: ".$result;
            $json=json_decode($result,true);
            $token=$json["token"];
            return($token);          
            curl_close($au);

        }
        
        public function generarqr($token,$transactionid,$moneda,$monto,$descripcion,$fvalides,$npagos,$modpagos)
        {
         
           

            $url_a ="$this->url"."api/qrsimple/generateQR";

            $qr = curl_init($url_a);

            //Json via POST
            $data1 = array(

                "transactionId" =>  "$transactionid", //258794626
                "accountCredit" =>  "$this->cta_encrypt", //cta_entriptada a depositar
                "currency"      =>  "$moneda",//BOB
                "amount"        =>  "$monto", //1.2
                "description"   =>  "$descripcion", //texto
                "dueDate"       =>  "$fvalides", //2023-03-20
                "singleUse"     =>  "$npagos", //true solo permite un solo pago //False permite varios pagos
                "modifyAmount"  =>  "$modpagos" //false no permite modificar el importe //true permite modificar el importe
            );

                $qrload = json_encode($data1);
                //print($qrload);
                //attach encoded JSON string to the POST fields
                curl_setopt($qr, CURLOPT_POSTFIELDS, $qrload);

                //set the content type to application/json
                /* set the content type json */

                $headers = [];
                $headers[] = 'Content-Type:application/json';
                $headers[] = "Authorization: Bearer ".$token;

                curl_setopt($qr, CURLOPT_HTTPHEADER, $headers);

                //return response instead of outputting
                curl_setopt($qr, CURLOPT_RETURNTRANSFER, true);

                //execute the POST request
                $result_qr = curl_exec($qr);

                //echo "<br>";
                //echo "<br>";
                //print "Respuesta de JSON QR:" .$result_qr;

                $json=json_decode($result_qr,true);
                $qrId=$json["qrId"];
                $imagen=$json["qrImage"];
                //close cURL resource
                echo '<img src="data:image/gif;base64,' . $imagen . '" />';
                echo "<label>$qrId</label>";
                curl_close($qr);
                    }


  

        public function cancelarqr($rqid,$token)
        {
        
            $url_c ="https://apimktdesa.baneco.com.bo/ApiGateway/api/qrsimple/cancelQR";

            $cancelqr = curl_init($url_c);
            
            //Json via POST
            $data1 = array(
            
                "qrId" =>  "$rqid"
            );
            
            $qrload = json_encode($data1);
            //print($qrload);
            //attach encoded JSON string to the POST fields
            curl_setopt($cancelqr, CURLOPT_POSTFIELDS, $qrload);
            curl_setopt($cancelqr, CURLOPT_CUSTOMREQUEST,"DELETE");
            
            
            //set the content type to application/json
             /* set the content type json */
             $headers = [];
             
             $headers[] = 'Content-Type:application/json';
             $headers[] = "Authorization: Bearer ".$token;
            
             curl_setopt($cancelqr, CURLOPT_HTTPHEADER, $headers);
            
            //return response instead of outputting
            curl_setopt($cancelqr, CURLOPT_RETURNTRANSFER, true);
            
            //execute the POST request
            $result_qr = curl_exec($cancelqr);
            
            echo "<br>";
            echo "<br>";
            print "Respuesta de JSON QR:" .$result_qr;
            curl_close($cancelqr);
        }

    }
?>