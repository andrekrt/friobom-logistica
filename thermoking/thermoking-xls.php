<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] == 99){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT idthermoking, placa_veiculo, tipo_veiculo, tipo_tk, hora_atual, hora_ultima_revisao, ultima_revisao_tk, hora_restante, situacao FROM thermoking LEFT JOIN veiculos ON thermoking.veiculo = veiculos.cod_interno_veiculo");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=thermoking.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "ID",
        mb_convert_encoding('Placa de Veículo','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Modelo de Veículo','ISO-8859-1', 'UTF-8'),
        "Tipo TK",
        mb_convert_encoding('Horímetro','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Horímetro Última Revisão','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Data Última Revisão','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Horímetro Restante','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Situação','ISO-8859-1', 'UTF-8')
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding($dado,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


