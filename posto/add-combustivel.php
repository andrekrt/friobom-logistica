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
    $valorlitro = str_replace(",", ".", filter_input(INPUT_POST, 'vlLitro'));
    $totalLitro =  str_replace(",", ".", filter_input(INPUT_POST, 'totalLt'));
    $frete = str_replace(",", ".", filter_input(INPUT_POST, 'frete'));
    $valorTotal = number_format(($valorlitro * $totalLitro) + $frete, 2, ".", "");
    $fornecedor = filter_input(INPUT_POST, 'fornecedor');
    $qualidade = filter_input(INPUT_POST, 'qualidade');
    $situacao = "Em Análise";
    $nf = filter_input(INPUT_POST, 'nf');
    $dataNf = filter_input(INPUT_POST, 'dataNf');

    $db->beginTransaction();

    try{
        // verificar se essa nota ja existe
        $verificaNf = $db->prepare("SELECT * FROM combustivel_entrada WHERE nf=:nf");
        $verificaNf->bindValue(':nf', $nf);
        $verificaNf->execute();
        if ($verificaNf->rowCount() > 0) {
            $_SESSION['msg'] = 'NF:' .$nf .'já registrada!';
            $_SESSION['icon']='warning';
            header("Location: entradas.php");
            exit();

        } 
        $inserir = $db->prepare("INSERT INTO combustivel_entrada (data_entrada, total_litros, valor_litro, frete, valor_total, nf, data_nf, fornecedor, qualidade, situacao, usuario) VALUES (:dataEntrada, :totalLitros, :valorLitros, :frete, :valorTotal, :nf, :dataNf,:fornecedor, :qualidade, :situacao, :usuario)");
        $inserir->bindValue(':dataEntrada', $dataEntrada);
        $inserir->bindValue(':totalLitros', $totalLitro);
        $inserir->bindValue(':valorLitros', $valorlitro);
        $inserir->bindValue(':frete', $frete);
        $inserir->bindValue(':valorTotal', $valorTotal);
        $inserir->bindValue(':nf', $nf);
        $inserir->bindValue(':dataNf', $dataNf);
        $inserir->bindValue(':fornecedor', $fornecedor);
        $inserir->bindValue(':qualidade', $qualidade);
        $inserir->bindValue(':situacao', $situacao);
        $inserir->bindValue(':usuario', $usuario);
        $inserir->execute();

        $db->commit();

        $_SESSION['msg'] = 'Entrada Lançada com Sucesso!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Registrar Ocorrência';
        $_SESSION['icon']='error';
    }

} else {
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}

header("Location: entradas.php");
exit();