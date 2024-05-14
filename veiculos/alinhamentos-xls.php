<?php

session_start();
require("../conexao.php");

$idModudulo = 1;
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
        $condicao = "AND alinhamentos_veiculo.filial=$filial";
    }


    $db->exec("set names utf8");
    $sql = $db->query("SELECT data_alinhamento, placa_veiculo, km_alinhamento, tipo_alinhamento FROM `alinhamentos_veiculo` WHERE 1 $condicao");

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


