<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] != 4 ){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT placa_veiculo, tipo_veiculo, COUNT(placa_veiculo) as qtdViagem, SUM(km_rodado) as kmRodado, SUM(litros) as totalLitros, SUM(valor_total_abast) as valorLitros, COUNT(solicitacoes_new.placa) AS qtdSolicitacoes, SUM(solicitacoes_new.vl_total) as valorTotalSolicitacoes, (SUM(solicitacoes_new.vl_total)+SUM(litros)) / SUM(km_rodado) FROM `viagem` LEFT JOIN solicitacoes_new ON viagem.placa_veiculo = solicitacoes_new.placa GROUP BY placa_veiculo ORDER BY placa_veiculo");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=despesas-por-veiculo.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Placa",
        "Tipo",
        "Qtd Viagens",
        "Km Rodado",
        "Total Abastecido",
        "Valor Abastecido",
        "Qtd Reparos",
        "Valor Reparos",
        mb_convert_encoding('R$/Km','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) ,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


