<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT idservicos, descricao, categoria, nome_usuario FROM servicos_almoxarifado LEFT JOIN usuarios ON servicos_almoxarifado.usuario = usuarios.idusuarios");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=servicos.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "ID",
        mb_convert_encoding('Descrição','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Categoria','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Lançado','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        
        fputcsv($arquivo, mb_convert_encoding($dado, 'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


