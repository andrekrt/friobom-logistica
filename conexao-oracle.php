<?php 
$config = array();
date_default_timezone_set('America/Sao_Paulo');

//error_reporting(0);


global $db;  // WINTHOR


//BANCO ORACLE WINTHOR PROD
/**/
$config['dbuser'] = 'bastosdesenvolvimento';
$config['dbpass'] = 'bastosdesenvolvimento';

$tns = "  
(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.10.65)(PORT = 1521))
    )
    (CONNECT_DATA =
      (SID = WINT)
    )
  )";  


  
try{
    $dbora = new PDO("oci:dbname=".$tns,$config['dbuser'],$config['dbpass']);

}catch(PDOException $e){
        echo ($e->getMessage());
      //echo 'Sem conexao com Winhor!';  
    }

?>