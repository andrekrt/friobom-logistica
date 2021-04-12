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

function atualizaEStoque($qtdEntrada, $qtdSaida, $qtdEstoque, $estoqueMinimo, $idPeca){
    require("../conexao.php");

    if($qtdEstoque<$estoqueMinimo){
        $situacao = "SOLICITAR";
    }else{
        $situacao = "OK";
    }

    $atualiza = $db->prepare("UPDATE peca_estoque SET total_entrada = :totalEntrada, total_saida = :totalSaida, total_estoque = :totalEstoque, situacao = :situacao WHERE idpeca = :idPeca ");
    $atualiza->bindValue(':totalEntrada', $qtdEntrada);   
    $atualiza->bindValue(':totalSaida', $qtdSaida);    
    $atualiza->bindValue(':totalEstoque', $qtdEstoque);  
    $atualiza->bindValue(':idPeca', $idPeca);  
    $atualiza->bindValue(':situacao', $situacao);   
    $atualiza->execute();

}

?>