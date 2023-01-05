<?php

function contaEstoque(){
    require("../conexao.php");

    //qtd de ENTRADAS
    $entradas = $db->query("SELECT SUM(total_litros) as totalLitros FROM combustivel_entrada WHERE situacao = 'Aprovado'");
    $qtdEntradas = $entradas->fetch();
    $qtdEntradas = $qtdEntradas['totalLitros']?$qtdEntradas['totalLitros']:0;

    //qtd saidas
    $saidas = $db->query("SELECT SUM(litro_abastecimento) as litroAbastecimento FROM combustivel_saida");
    $qtdSaidas = $saidas->fetch();
    $qtdSaidas = $qtdSaidas['litroAbastecimento']?$qtdSaidas['litroAbastecimento']:0;

    $estoqueAtual = $qtdEntradas-$qtdSaidas;

    return $estoqueAtual;

}
?>