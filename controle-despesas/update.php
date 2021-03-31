<?php

session_start();
require("../conexao.php");

$sql = $db->query("SELECT * FROM viagem");
$dados = $sql->fetchAll();

if($dados['media_comtk']<0){
    echo $dados['media_comtk']."<br>";
}


?>