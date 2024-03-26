<?php

session_start();
require("../conexao.php");
include("funcao.php");

$idModudulo = 13;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo, PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result > 0)) {

    $usuario = $_SESSION['idUsuario'];

    $id = filter_input(INPUT_GET, 'id');
    $situacao = "Aprovado";

    $db->beginTransaction();

    try {
        $atualiza = $db->prepare("UPDATE combustivel_saida SET situacao=:situacao WHERE idcombustivel_saida=:id");
        $atualiza->bindValue(':situacao', $situacao);
        $atualiza->bindValue(':id', $id);
        $atualiza->execute();

        $abastecimento = $db->prepare("SELECT * FROM combustivel_saida WHERE idcombustivel_saida=:id");
        $abastecimento->bindValue(':id', $id);
        $abastecimento->execute();
        $dados = $abastecimento->fetch();

        $sqlExtrato = $db->prepare("INSERT INTO combustivel_extrato (data_operacao, tipo_operacao, volume,  usuario) VALUES (:dataOp, :tipoOp, :volume, :usuario)");
        $sqlExtrato->bindValue(':dataOp', $dados['data_abastecimento']);
        $sqlExtrato->bindValue(':tipoOp', "Perca");
        $sqlExtrato->bindValue(':volume', $dados['litro_abastecimento']);
        $sqlExtrato->bindValue(':usuario', $dados['usuario']);
        $sqlExtrato->execute();

        $db->commit();
        $_SESSION['msg'] = 'Perca Aprovada com Sucesso!';
        $_SESSION['icon']='success';
        
    } catch (Exception $e) {
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Aprovar Perca';
        $_SESSION['icon']='error';
    }
} else {
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning'; 
}
header("Location: abastecimento.php");
exit();