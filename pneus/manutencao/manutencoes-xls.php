<?php

session_start();
require("../../conexao.php");

if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT data_manutencao, tipo_manutencao, num_fogo, medida, marca, modelo, vida, km_veiculo, km_pneu, valor, num_nf, fornecedor, manutencao_pneu.suco01, manutencao_pneu.suco02, manutencao_pneu.suco03, manutencao_pneu.suco04, nome_usuario FROM manutencao_pneu LEFT JOIN pneus ON manutencao_pneu.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON manutencao_pneu.usuario = usuarios.idusuarios");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=manutencoes.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Data de Reparo",
        "Tipo de Reparo",
        mb_convert_encoding('Nº Fogo','ISO-8859-1', 'UTF-8'),
        "Medida",
        "Marca",
        "Modelo",
        "Vida",
        mb_convert_encoding('Km Veículo','ISO-8859-1', 'UTF-8'),
        "Km Pneu",
        "Valor",
        mb_convert_encoding('Nº NF','ISO-8859-1', 'UTF-8'),
        "Fornecedor",
        "Suco 01",
        "Suco 02",
        "Suco 03",
        "Suco 04",
        "Registrado"
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) ,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


