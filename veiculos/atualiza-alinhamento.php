<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

$idModudulo = 1;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idAlinhamento = filter_input(INPUT_POST, 'id');
    $placa = filter_input(INPUT_POST, 'placa');
    $kmAlinhamento = filter_input(INPUT_POST, 'kmAlinhamento');
    $dataAlinhamento = filter_input(INPUT_POST, 'dataAlinhamento');
    $tipoAlinhamento = filter_input(INPUT_POST, 'tipo');

    $db->beginTransaction();

    try{

        $atualiza = $db->prepare("UPDATE alinhamentos_veiculo SET data_alinhamento = :dataAlinhamento, km_alinhamento = :kmAlinhamento, placa_veiculo = :placa, tipo_alinhamento = :tipo WHERE idalinhamento = :id");
        $atualiza->bindValue(':placa', $placa);
        $atualiza->bindValue(':kmAlinhamento', $kmAlinhamento);
        $atualiza->bindValue(':dataAlinhamento', $dataAlinhamento);
        $atualiza->bindValue(':id', $idAlinhamento);
        $atualiza->bindValue(':tipo', $tipoAlinhamento);
        $atualiza->execute();

        $atualizaVeiculo = $db->prepare("UPDATE veiculos SET km_alinhamento = :kmAlinhamento WHERE placa_veiculo = :placa");
        $atualizaVeiculo->bindValue(':placa', $placa);
        $atualizaVeiculo->bindValue(':kmAlinhamento', $kmAlinhamento);
        $atualizaVeiculo->execute();

        $db->commit();
        $_SESSION['msg'] = 'Alinhamento Atualizado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();

        $_SESSION['msg'] = 'Erro ao Atualizar Alinhamento';
        $_SESSION['icon']='error';
    }

    header("Location: alinhamentos.php");
    exit(); 

}else{

}

?>