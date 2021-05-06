<?php

require("../conexao.php");

function contaEntradas($idPeca){
    require("../conexao.php");

    $totalEntradas = $db->prepare("SELECT SUM(qtd) as entradas FROM entrada_estoque WHERE peca_idpeca = :idPeca");
    $totalEntradas->bindValue(':idPeca', $idPeca);
    $totalEntradas->execute();
    $entradas = $totalEntradas->fetch();

    return $entradas['entradas'];
}

function contaSaida($idPeca){
    require("../conexao.php");

    $totalSaida = $db->prepare("SELECT SUM(qtd) as saidas FROM saida_estoque WHERE peca_idpeca = :idPeca");
    $totalSaida->bindValue(':idPeca', $idPeca);
    $totalSaida->execute();
    $saidas = $totalSaida->fetch();

    return $saidas['saidas'];
}

function valorTotalPeca($idPeca){
    require("../conexao.php");

    $totalComprado = $db->prepare("SELECT SUM(vl_total_comprado) as totalComprado FROM entrada_estoque WHERE peca_idpeca = :idPeca");
    $totalComprado->bindValue(':idPeca', $idPeca);
    $totalComprado->execute();
    $totalComprado = $totalComprado->fetch();

    return $totalComprado['totalComprado'];

}

function atualizaEStoque($qtdEntrada, $qtdSaida, $qtdEstoque, $estoqueMinimo, $valorComprado, $idPeca){
    require("../conexao.php");

    if($qtdEstoque<$estoqueMinimo){
        $situacao = "SOLICITAR";
    }else{
        $situacao = "OK";
    }

    $atualiza = $db->prepare("UPDATE peca_estoque SET total_entrada = :totalEntrada, total_saida = :totalSaida, total_estoque = :totalEstoque, situacao = :situacao, valor_total = :totalComprado WHERE idpeca = :idPeca ");
    $atualiza->bindValue(':totalEntrada', $qtdEntrada);   
    $atualiza->bindValue(':totalSaida', $qtdSaida);    
    $atualiza->bindValue(':totalEstoque', $qtdEstoque);  
    $atualiza->bindValue(':idPeca', $idPeca);  
    $atualiza->bindValue(':situacao', $situacao);   
    $atualiza->bindValue(':totalComprado', $valorComprado);
    $atualiza->execute();
    
}



?>