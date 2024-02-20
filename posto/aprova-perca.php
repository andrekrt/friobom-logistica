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
        echo "<script>alert('Perca Aprovada com Sucesso!');</script>";
        echo "<script>window.location.href='abastecimento.php'</script>";
    } catch (Exception $e) {
        echo "Erro " . $e->getMessage();
        $db->rollBack();
    }
} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='abastecimento.php'</script>";
}
