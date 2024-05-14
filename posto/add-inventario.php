<?php

session_start();
require("../conexao.php");
include "funcao.php";

$idModudulo = 13;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    $usuario = $_SESSION['idUsuario'];
    $dataInventario = date("Y-m-d");
    $litros = str_replace(",",".",filter_input(INPUT_POST, 'litros'));
    $volumeAnterior = contaEstoque();
    $volumeDivergente = $volumeAnterior-$litros;

    $db->beginTransaction();

    try{
        $inserir = $db->prepare("INSERT INTO combustivel_inventario (data_inventario , qtd_encontrada, usuario, filial) VALUES (:dataInventario, :litros, :usuario, :filial)");
        $inserir->bindValue(':dataInventario', $dataInventario);
        $inserir->bindValue(':litros', $litros);
        $inserir->bindValue(':usuario', $usuario);
        $inserir->bindValue(':filial', $filial);
        $inserir->execute();

        $sqlExtrato = $db->prepare("INSERT INTO combustivel_extrato (data_operacao, tipo_operacao, volume, usuario, filial) VALUES (:dataOp, :tipoOp, :volume, :usuario, :filial)");
        $sqlExtrato->bindValue(':dataOp', $dataInventario);
        $sqlExtrato->bindValue(':tipoOp', "Inventário");
        $sqlExtrato->bindValue(':volume', $litros);
        $sqlExtrato->bindValue(':usuario', $usuario);
        $sqlExtrato->bindValue(':filial', $filial);
        $sqlExtrato->execute();

        $db->commit();

        $_SESSION['msg'] = 'Inventário Registrado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Registrar Inventário';
        $_SESSION['icon']='error';
    }

    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}

header("Location: inventario.php");
exit();
?>