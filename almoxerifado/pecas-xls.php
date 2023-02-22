<?php

session_start();
require("../conexao-on.php");

if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT idpeca, descricao_peca, un_medida, grupo_peca, estoque_minimo, total_entrada, total_saida, total_estoque, valor_total, situacao, data_cadastro, nome_usuario FROM `peca_estoque` LEFT JOIN usuarios ON peca_estoque.id_usuario = usuarios.idusuarios");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=estoque.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "ID",
        mb_convert_encoding('Descrição Peça','ISO-8859-1', 'UTF-8'),
        "Medida",
        "Grupo",
        mb_convert_encoding('Estoque Mínimo','ISO-8859-1', 'UTF-8'),
        "Total Entrada",
        mb_convert_encoding('Total Saída','ISO-8859-1', 'UTF-8'),
        "Total Estoque",
        "Total Comprado",
        mb_convert_encoding('Situação','ISO-8859-1', 'UTF-8'),
        "Data Cadastro",
        mb_convert_encoding('Usuário que Cadastrou','ISO-8859-1', 'UTF-8')
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) , 'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


