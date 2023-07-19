<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

$idModudulo = 2;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idTk = filter_input(INPUT_POST, 'id');
    $placa = filter_input(INPUT_POST, 'placa');
    $tipoTk = filter_input(INPUT_POST, 'tipotk');
    $horimetro = filter_input(INPUT_POST, 'horaAtual');

    //echo "$idRevisao<br>$placa<br>$kmRevisao<br>$dataRevisao<br>$tipoRevisao";

    $atualiza = $db->prepare("UPDATE thermoking SET veiculo = :placa, tipo_tk = :tipotk, hora_atual = :horimetro WHERE idthermoking = :id");
    $atualiza->bindValue(':placa', $placa);
    $atualiza->bindValue(':tipotk', $tipoTk);
    $atualiza->bindValue(':horimetro', $horimetro);
    $atualiza->bindValue(':id', $idTk);
    
    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='thermoking.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{

}

?>