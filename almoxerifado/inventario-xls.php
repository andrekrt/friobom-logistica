<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT idinventario, data_inv, CONCAT(idpeca,' - ', descricao_peca) as peca, grupo_peca, un_medida, qtd, nome_usuario FROM inventario_almoxarifado LEFT JOIN usuarios ON inventario_almoxarifado.usuario = usuarios.idusuarios LEFT JOIN peca_estoque ON inventario_almoxarifado.peca = peca_estoque.idpeca");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=inventario.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "ID",
        "Data",
        mb_convert_encoding('Peça','ISO-8859-1', 'UTF-8'),
        "Grupo",
        "Medida",
        "Qtd",
        mb_convert_encoding('Lançado','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) , 'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


