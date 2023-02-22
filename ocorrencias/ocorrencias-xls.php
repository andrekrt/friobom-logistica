<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario'] == 2){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT idocorrencia, nome_motorista, data_ocorrencia, tipo_ocorrencia, advertencia, laudo, descricao_custos, vl_total_custos, situacao FROM `ocorrencias`LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista LEFt JOIN usuarios ON ocorrencias.usuario_lancou = usuarios.idusuarios");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=ocorrencias.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "ID",
        "Motorista",
        mb_convert_encoding('Data Ocorrência','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Tipo ocorrência','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Advertência','ISO-8859-1', 'UTF-8'),
        "Laudo",
        mb_convert_encoding('Descrição','ISO-8859-1', 'UTF-8'),
        "Valor Total",
        mb_convert_encoding('Situação','ISO-8859-1', 'UTF-8')
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) ,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


