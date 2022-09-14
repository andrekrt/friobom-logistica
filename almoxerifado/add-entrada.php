<?php

session_start();
require("../conexao.php");
include('funcoes.php');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false&& $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){
    
    $idUsuario = $_SESSION['idUsuario'];
    $dataNota = filter_input(INPUT_POST, 'dataNota')?filter_input(INPUT_POST, 'dataNota'):null;
    $numNf = filter_input(INPUT_POST, 'numNF')?filter_input(INPUT_POST, 'numNF'):null;
    $numPedido = filter_input(INPUT_POST, 'pedido')?filter_input(INPUT_POST, 'pedido'):null;
    $peca = filter_input(INPUT_POST, 'peca');
    $preco = str_replace(",", ".",filter_input(INPUT_POST, 'preco')) ;
    $qtd = str_replace(",",".",filter_input(INPUT_POST, 'qtd') ) ;
    $desconto = str_replace(",", ".",filter_input(INPUT_POST, 'desconto') ) ;
    $obsEntrada = filter_input(INPUT_POST, 'obsEntrada')?filter_input(INPUT_POST, 'obsEntrada'):null;
    $fornecedor = filter_input(INPUT_POST, 'fornecedor');
    $totalComprado = ($preco*$qtd)-$desconto;

    $inserir = $db->prepare("INSERT INTO entrada_estoque (data_nf, num_nf, num_pedido, peca_idpeca, preco_custo, qtd, desconto, obs, fornecedor, vl_total_comprado, id_usuario) VALUES (:dataNF, :numNF, :numPedido, :idPeca, :precoCusto, :qtd, :desconto, :obs, :fornecedor, :totalComprado, :idUsuario)");
    $inserir->bindValue(':dataNF', $dataNota);
    $inserir->bindValue(':numNF', $numNf);
    $inserir->bindValue(':idPeca', $peca);
    $inserir->bindValue(':precoCusto', $preco);
    $inserir->bindValue(':qtd', $qtd);
    $inserir->bindValue(':desconto', $desconto);
    $inserir->bindValue(':obs', $obsEntrada);
    $inserir->bindValue(':fornecedor', $fornecedor);
    $inserir->bindValue(':totalComprado', $totalComprado);
    $inserir->bindValue(':idUsuario', $idUsuario);
    $inserir->bindValue(':numPedido', $numPedido);

    if($inserir->execute()){
        atualizaEStoque($peca);
        echo "<script>alert('Entrada Lançada com Sucesso!');</script>";
        echo "<script>window.location.href='entradas.php'</script>";    
        
    }else{
        print_r($inserir->errorInfo());
    }

}

?>