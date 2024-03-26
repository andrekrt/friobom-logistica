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

    $db->beginTransaction();

    try{
        //verificar se o veiculo já tem tk vinculado
        $sqlConsulta = $db->prepare("SELECT veiculo FROM thermoking WHERE veiculo = :veiculo");
        $sqlConsulta->bindValue(':veiculo', $placa);
        $sqlConsulta->execute();
        if($sqlConsulta->rowCount()>0){
            $_SESSION['msg'] = 'Esse veículo já tem Thermoking vinculado!';
            $_SESSION['icon']='warning';

            header("Location: thermoking.php");
            exit();
            
        }
        $inserir = $db->prepare("INSERT INTO thermoking (veiculo, tipo_tk, hora_atual) VALUES (:veiculo, :tipoTk, :horimetro)");
        $inserir->bindValue(':veiculo', $placa);
        $inserir->bindValue(':tipoTk', $tipoTk);
        $inserir->bindValue(':horimetro', $horimetro);  
        $inserir->execute();
        
        $db->commit();

        $_SESSION['msg'] = 'Thermoking Cadastrado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Thermoking';
        $_SESSION['icon']='error';
    } 

    header("Location: thermoking.php");
    exit();

}

?>