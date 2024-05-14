<?php

function addEstoque($peca, $fornecedor, $qtd, $valorUn, $desconto, $valorTotal, $numNf, $obs, $frete, $usuario){
    session_start();
    require("../conexao.php");
    include_once('../almoxerifado/funcoes.php');

    $filial = $_SESSION['filial'];
    $dataSaida = date("Y-m-d");
    $inserir = $db->prepare("INSERT INTO entrada_estoque (data_nf, num_nf, peca_idpeca, preco_custo, qtd, frete, desconto, obs, fornecedor, vl_total_comprado, id_usuario, filial) VALUES (:dataNf, :nf, :peca, :preco, :qtd, :frete, :desconto, :obs, :fornecedor, :vlTotal, :usuario, :filial)");
    $inserir->bindValue(':dataNf', $dataSaida);
    $inserir->bindValue(':nf', $numNf);
    $inserir->bindValue(':peca', $peca);
    $inserir->bindValue(':preco', $valorUn);
    $inserir->bindValue(':qtd', $qtd);
    $inserir->bindValue(':frete', $frete);
    $inserir->bindValue(':desconto', $desconto);
    $inserir->bindValue(':obs', $obs);
    $inserir->bindValue(':fornecedor', $fornecedor);
    $inserir->bindValue(':vlTotal', $valorTotal);
    $inserir->bindValue(':usuario', $usuario);
    $inserir->bindValue(':filial', $filial);

    // echo $peca . "<br>";

    if($inserir->execute()){  
        atualizaEStoque($peca);
        return true;    
    }else{
        return false;
    }

}

?>