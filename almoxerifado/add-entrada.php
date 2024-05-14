<?php

session_start();
require("../conexao.php");
include('funcoes.php');

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
    $idUsuario = $_SESSION['idUsuario'];
    $dataNota = filter_input(INPUT_POST, 'dataNota')?filter_input(INPUT_POST, 'dataNota'):null;
    $numNf = filter_input(INPUT_POST, 'numNF')?filter_input(INPUT_POST, 'numNF'):null;
    $numPedido = filter_input(INPUT_POST, 'pedido')?filter_input(INPUT_POST, 'pedido'):null;
    $peca = filter_input(INPUT_POST, 'peca');
    $preco = str_replace(",", ".",filter_input(INPUT_POST, 'preco')) ;
    $qtd = str_replace(",",".",filter_input(INPUT_POST, 'qtd') ) ;
    $desconto = str_replace(",", ".",filter_input(INPUT_POST, 'desconto') ) ;
    $frete = str_replace(",", ".",filter_input(INPUT_POST, 'frete') );
    $obsEntrada = filter_input(INPUT_POST, 'obsEntrada')?filter_input(INPUT_POST, 'obsEntrada'):null;
    $fornecedor = filter_input(INPUT_POST, 'fornecedor');
    $totalComprado = (($preco*$qtd)+$frete)-$desconto;
    $filial = $_SESSION['filial'];

    $db->beginTransaction();

    try{
        $inserir = $db->prepare("INSERT INTO entrada_estoque (data_nf, num_nf, num_pedido, peca_idpeca, preco_custo, qtd,frete, desconto, obs, fornecedor, vl_total_comprado, id_usuario, filial) VALUES (:dataNF, :numNF, :numPedido, :idPeca, :precoCusto, :qtd, :frete, :desconto, :obs, :fornecedor, :totalComprado, :idUsuario, :filial)");
        $inserir->bindValue(':dataNF', $dataNota);
        $inserir->bindValue(':numNF', $numNf);
        $inserir->bindValue(':idPeca', $peca);
        $inserir->bindValue(':precoCusto', $preco);
        $inserir->bindValue(':qtd', $qtd);
        $inserir->bindValue(':frete',$frete);
        $inserir->bindValue(':desconto', $desconto);
        $inserir->bindValue(':obs', $obsEntrada);
        $inserir->bindValue(':fornecedor', $fornecedor);
        $inserir->bindValue(':totalComprado', $totalComprado);
        $inserir->bindValue(':idUsuario', $idUsuario);
        $inserir->bindValue(':numPedido', $numPedido);
        $inserir->bindValue(':filial', $filial);
        $inserir->execute();

        atualizaEStoque($peca);

        $db->commit();

        $_SESSION['msg'] = 'Entrada Lançada com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Vale';
        $_SESSION['icon']='error';
    }
    header("Location: entradas.php");
    exit();
}

?>