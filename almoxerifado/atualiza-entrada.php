<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99){

    $idEntrada = filter_input(INPUT_POST,'identrada');
    $dataNota = filter_input(INPUT_POST, 'dataNf')?filter_input(INPUT_POST, 'dataNf'):null;
    $numNf = filter_input(INPUT_POST, 'numNf')?filter_input(INPUT_POST, 'numNf'):null;
    $numPedido = filter_input(INPUT_POST, 'numPedido')?filter_input(INPUT_POST, 'numPedido'):null;
    $peca = filter_input(INPUT_POST, 'peca');
    $preco = str_replace(",", ".",filter_input(INPUT_POST, 'preco')) ;
    $qtd = str_replace(",",".",filter_input(INPUT_POST, 'qtd') ) ;
    $desconto = str_replace(",", ".",filter_input(INPUT_POST, 'desconto') ) ;
    $obsEntrada = filter_input(INPUT_POST, 'obs')?filter_input(INPUT_POST, 'obs'):null;
    $fornecedor = filter_input(INPUT_POST, 'fornecedor');
    $totalComprado = ($preco*$qtd)-$desconto;
    

    $atualiza = $db->prepare(" UPDATE entrada_estoque SET data_nf = :dataNf, num_nf = :numNf, num_pedido = :numPedido, peca_idpeca = :peca, preco_custo = :precoCusto, qtd = :qtd, desconto = :desconto, obs = :obs, fornecedor = :fornecedor, vl_total_comprado = :valorTotal WHERE identrada_estoque = :idEntrada");
    $atualiza->bindValue(':dataNf', $dataNota);
    $atualiza->bindValue(':numNf', $numNf);
    $atualiza->bindValue(':numPedido', $numPedido);
    $atualiza->bindValue(':peca', $peca);
    $atualiza->bindValue(':precoCusto', $preco);
    $atualiza->bindValue(':qtd', $qtd);
    $atualiza->bindValue(':desconto', $desconto);
    $atualiza->bindValue(':obs', $obsEntrada);
    $atualiza->bindValue(':fornecedor', $fornecedor);
    $atualiza->bindValue(':valorTotal', $totalComprado);
    $atualiza->bindValue(':idEntrada', $idEntrada);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='entradas.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>