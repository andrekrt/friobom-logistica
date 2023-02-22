<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] != 4 ){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT placa_veiculo, km_revisao, data_revisao FROM `revisao_veiculos`");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=revisoes.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Placa",
        mb_convert_encoding('Km Revisão','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Data Revisão','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding($dado,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


