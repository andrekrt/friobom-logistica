<?php

function contaEstoque(){
    require("../conexao.php");
    session_start();

    $filial = $_SESSION['filial'];

    //qtd de ENTRADAS
    $entradas = $db->query("SELECT SUM(total_litros) as totalLitros FROM combustivel_entrada WHERE situacao = 'Aprovado' AND  filial = $filial ");
    $qtdEntradas = $entradas->fetch();
    $qtdEntradas = $qtdEntradas['totalLitros']?$qtdEntradas['totalLitros']:0;

    //qtd saidas
    $saidas = $db->query("SELECT SUM(litro_abastecimento) as litroAbastecimento FROM combustivel_saida WHERE situacao = 'Aprovado' AND filial = $filial");
    $qtdSaidas = $saidas->fetch();
    $qtdSaidas = $qtdSaidas['litroAbastecimento']?$qtdSaidas['litroAbastecimento']:0;

    $estoqueAtual = $qtdEntradas-$qtdSaidas;

    return $estoqueAtual;

}
?>