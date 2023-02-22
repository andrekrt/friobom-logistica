<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] == 99){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT idrevisao, placa_veiculo, tipo_veiculo, tipo_tk, data_revisao_tk, horimetro_revisao FROM revisao_tk LEFT JOIN thermoking ON revisao_tk.thermoking = thermoking.idthermoking LEFT JOIN veiculos ON thermoking.veiculo = veiculos.cod_interno_veiculo");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=revisao-tk.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "ID",
        mb_convert_encoding('Placa de Veículo','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Modelo de Veículo','ISO-8859-1', 'UTF-8'),
        "Tipo TK",
        mb_convert_encoding('Data Revisão','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Horímetro Revisão','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding($dado,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


