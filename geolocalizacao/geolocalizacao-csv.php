<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario']==2 || $_SESSION['tipoUsuario']==99){
    $filial = $_SESSION['filial'];
    if($filial===99){
        $condicao = " ";
    }else{
        $condicao = "AND localizacao.filial=$filial";
    }

    $db->exec("set names utf8");
    $sql = $db->query("SELECT * FROM localizacao WHERE 1 $condicao");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=geolocalizacao.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Filial",
        "ID",
        "Data",
        "Supervisor",
        mb_convert_encoding('Código Cliente','ISO-8859-1', 'UTF-8'),
        "RCA",
        mb_convert_encoding('Endereço','ISO-8859-1', 'UTF-8'),
        "Bairro",
        "Cidade",
        mb_convert_encoding('Situação','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fwrite($arquivo,
            "\n". $dado['filial'].";". $dado['id'].";". date("d/m/Y h:i",strtotime($dado['data_hora'])).";". $dado['codigo_sup']. ";".$dado['cod_cliente']. ";". $dado['rca'].";" .mb_convert_encoding($dado['endereco'],'ISO-8859-1', 'UTF-8').";". mb_convert_encoding($dado['bairro'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['cidade'],'ISO-8859-1', 'UTF-8').";". mb_convert_encoding($dado['situacao'],'ISO-8859-1', 'UTF-8')
        );
    }

    fclose($arquivo);
}


