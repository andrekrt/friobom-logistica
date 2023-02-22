<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] != 4 ){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT data_alinhamento, placa_veiculo, km_alinhamento, tipo_alinhamento FROM `alinhamentos_veiculo`");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=alinhamentos.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Data Alinhamento",
        mb_convert_encoding('Placa Veículo','ISO-8859-1', 'UTF-8'),
        "Km Alinhamento",
        "Tipo Alinhamento"
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding($dado,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


