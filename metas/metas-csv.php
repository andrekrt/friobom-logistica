<?php

session_start();
require("../conexao.php");

$idModudulo = 15;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    if($filial===99){
        $condicao = " ";
    }else{
        $condicao = "AND metas.filial=$filial";
    }

    $db->exec("set names utf8");
    $sql = $db->query("SELECT * FROM metas WHERE 1 $condicao");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=metas.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Filial",
        mb_convert_encoding('Código','ISO-8859-1', 'UTF-8'),
        "Tipo Meta",
        "Data",
        "Meta",
        mb_convert_encoding('Alncançado','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Atingido','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        $percentual =number_format($dado['valor_alcancado']/$dado['valor_meta'],2,",",".");
        fwrite($arquivo,
            "\n". $dado['filial'].";". $dado['token'].";".  mb_convert_encoding($dado['tipo_meta'],'ISO-8859-1', 'UTF-8').";". date("d/m/Y h:i",strtotime($dado['data_meta'])). ";".$dado['valor_meta']. ";". $dado['valor_alcancado'].";" .$percentual
        );
    }

    fclose($arquivo);
}


