<?php

session_start();
require("../conexao.php");

$idModudulo = 13;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo, PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result > 0)) {

    $usuario = $_SESSION['idUsuario'];
    $dataEntrada = date("Y-m-d");
    $totalLitro =  str_replace(",", ".", filter_input(INPUT_POST, 'ajustLt'));
    $situacao = "Em Análise";
    $nf= "Ajuste";

    $inserir = $db->prepare("INSERT INTO combustivel_entrada (data_entrada, nf, total_litros, situacao, usuario) VALUES (:dataEntrada, :nf,:totalLitros, :situacao, :usuario)");
    $inserir->bindValue(':dataEntrada', $dataEntrada);
    $inserir->bindValue(':nf', $nf);
    $inserir->bindValue(':totalLitros', $totalLitro);
    $inserir->bindValue(':situacao', $situacao);
    $inserir->bindValue(':usuario', $usuario);

    if ($inserir->execute()) {
        echo "<script>alert('Entrada Lançada com Sucesso!');</script>";
        echo "<script>window.location.href='entradas.php'</script>";
    } else {
        print_r($inserir->errorInfo());
    }


} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='entradas.php'</script>";
}
