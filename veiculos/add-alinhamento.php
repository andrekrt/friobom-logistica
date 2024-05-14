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
    $filial = $_SESSION['filial'];
    $placa = filter_input(INPUT_POST,'placa');
    $kmAlinhamento = filter_input(INPUT_POST, 'kmAlinhamento');
    $dataAlinhamento = filter_input(INPUT_POST, 'dataAlinhamento');
    $tipoAlinhamento = filter_input(INPUT_POST, 'tipo');

    $db->beginTransaction();

    try{
        $inserir = $db->prepare("INSERT INTO alinhamentos_veiculo (data_alinhamento, placa_veiculo, km_alinhamento, tipo_alinhamento, filial) VALUES (:dataAlinhamento, :placa, :kmAlinhamento, :tipo, :filial)");
        $inserir->bindValue(':placa', $placa);
        $inserir->bindValue(':kmAlinhamento', $kmAlinhamento);
        $inserir->bindValue(':dataAlinhamento', $dataAlinhamento);
        $inserir->bindValue(':tipo', $tipoAlinhamento);    
        $inserir->bindValue(':filial', $filial);
        $inserir->execute();

        $atualizaVeiculo = $db->prepare("UPDATE veiculos SET km_alinhamento = :kmAlinhamento WHERE placa_veiculo = :placa");
        $atualizaVeiculo->bindValue(':kmAlinhamento', $kmAlinhamento);
        $atualizaVeiculo->bindValue(':placa',$placa);
        $atualizaVeiculo->execute();

        $db->commit();

        $_SESSION['msg'] = 'Alinhamento Lançado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Alinhamento';
        $_SESSION['icon']='error';
    }

    header("Location: alinhamentos.php");
    exit();

}

?>