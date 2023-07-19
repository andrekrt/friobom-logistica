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
    
    $placa = filter_input(INPUT_POST,'placa');
    $tipoTk = filter_input(INPUT_POST, 'tipotk');
    $horimetro = filter_input(INPUT_POST, 'horaAtual');
    
    //verificar se o veiculo já tem tk vinculado
    $sqlConsulta = $db->prepare("SELECT veiculo FROM thermoking WHERE veiculo = :veiculo");
    $sqlConsulta->bindValue(':veiculo', $placa);
    $sqlConsulta->execute();
    if($sqlConsulta->rowCount()>0){
        echo "<script>alert('Esse veículo já tem Thermoking vinculado!');</script>";
        echo "<script>window.location.href='thermoking.php'</script>";
    }else{
        $inserir = $db->prepare("INSERT INTO thermoking (veiculo, tipo_tk, hora_atual) VALUES (:veiculo, :tipoTk, :horimetro)");
        $inserir->bindValue(':veiculo', $placa);
        $inserir->bindValue(':tipoTk', $tipoTk);
        $inserir->bindValue(':horimetro', $horimetro);   
    
        if($inserir->execute()){
           echo "<script>alert('Thermoking Cadastrado!');</script>";
            echo "<script>window.location.href='thermoking.php'</script>";
            
        }else{
             print_r($inserir->errorInfo());
        }
    }

   

}

?>