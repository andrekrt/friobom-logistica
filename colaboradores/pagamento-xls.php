<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario'] == 4){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT mes_ano, pagamento, tipo_funcionarios, nome_usuario  FROM `folha_pagamento` LEFT JOIN usuarios ON folha_pagamento.usuario=usuarios.idusuarios;");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=folha-pagamento.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        mb_convert_encoding('Mês/Ano','ISO-8859-1', 'UTF-8'),
        "Valor",
        mb_convert_encoding('Grupo Funcinários','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Usuário','ISO-8859-1', 'UTF-8')
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) ,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


