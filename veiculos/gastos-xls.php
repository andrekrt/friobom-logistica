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
    $sql = $db->query("SELECT viagem.placa_veiculo, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(mediaSemTk) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, SUM(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia FROM viagem WHERE 1 AND $condicao GROUP BY placa_veiculo");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=relatorio-veiculos.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Placa",
        mb_convert_encoding('R$/Entrega','ISO-8859-1', 'UTF-8'),
        "Valor Transportado",
        "Valor Devolvido",
        "Qtd de Entrega",
        "Km Rodado",
        "Litros",
        "Valor Abastecido",
        "Km/L",
        "Dias em Rota",
        mb_convert_encoding('Valor Diária Motorista','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Valor Diária Ajudante','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Diárias Motoristas','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Diárias Ajudante','ISO-8859-1', 'UTF-8'),
        "Outros Gastos",
        "Tomada",
        "Descarga",
        "Travessia"
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) ,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}