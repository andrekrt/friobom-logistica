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

    // pegar valor do litro do ultimo registro
    $sqlEntrada = $db->prepare('SELECT valor_litro FROM combustivel_entrada WHERE valor_litro IS NOT NULL ORDER BY idcombustivel_entrada DESC LIMIT 1');
    $sqlEntrada->execute();
    $entrada = $sqlEntrada->fetch();
    $valorLitro = $entrada['valor_litro'];

    $inserir = $db->prepare("INSERT INTO combustivel_entrada (data_entrada, nf, valor_litro, total_litros, situacao, usuario) VALUES (:dataEntrada, :nf, :valorLitro,:totalLitros, :situacao, :usuario)");
    $inserir->bindValue(':dataEntrada', $dataEntrada);
    $inserir->bindValue(':nf', $nf);
    $inserir->bindValue(':valorLitro', $valorLitro);
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
