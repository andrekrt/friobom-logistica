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
        $condicao = "AND viagem.filial=$filial";
    }

    $db->exec("set names utf8");
    $sql = $db->query("SELECT viagem.filial,placa_veiculo, tipo_veiculo, COUNT(placa_veiculo) as qtdViagem, SUM(km_rodado) as kmRodado, SUM(litros) as totalLitros, SUM(valor_total_abast) as valorLitros, COUNT(solicitacoes_new.placa) AS qtdSolicitacoes, SUM(solicitacoes_new.vl_total) as valorTotalSolicitacoes, (SUM(solicitacoes_new.vl_total)+SUM(litros)) / SUM(km_rodado) FROM `viagem` LEFT JOIN solicitacoes_new ON viagem.placa_veiculo = solicitacoes_new.placa WHERE 1 $condicao GROUP BY placa_veiculo ORDER BY placa_veiculo");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=despesas-por-veiculo.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Filial",
        "Placa",
        "Tipo",
        "Qtd Viagens",
        "Km Rodado",
        "Total Abastecido",
        "Valor Abastecido",
        "Qtd Reparos",
        "Valor Reparos",
        mb_convert_encoding('R$/Km','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) ,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


