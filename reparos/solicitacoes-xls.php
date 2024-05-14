<?php

session_start();
require("../conexao.php");

$idModudulo = 9;
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
        $condicao = "AND solicitacoes_new.filial=$filial";
    }

    $db->exec("set names utf8");
    $lang = $db->query("SET lc_time_names = 'pt_PT'");
    $sql = $db->query("SELECT token, data_atual, monthname(data_atual), YEAR(data_atual), placa, veiculos.categoria as categoriaVeiculo, motorista, rota, problema, nome_fantasia, peca_reparo.categoria as categoriaPeca, descricao, qtd, un_medida, vl_unit, vl_total, frete, num_nf, nome_usuario, solicitacoes_new.situacao, data_aprovacao, obs FROM solicitacoes_new LEFT JOIN peca_reparo ON solicitacoes_new.peca_servico = peca_reparo.id_peca_reparo LEFT JOIN usuarios ON solicitacoes_new.usuario = usuarios.idusuarios LEFT JOIN veiculos ON solicitacoes_new.placa = veiculos.placa_veiculo LEFT JOIN fornecedores ON solicitacoes_new.fornecedor = fornecedores.id WHERE 1 $condicao ");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=solicitacoes.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Token",
        "Data",
        mb_convert_encoding('Mês','ISO-8859-1', 'UTF-8'),
        "Ano",
        "Placa",
        mb_convert_encoding('Categoria Veículo','ISO-8859-1', 'UTF-8'),
        "Motorista",
        "Rota",
        "Problema",
        "Fornecedor",
        mb_convert_encoding('Categoria Peça','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Peça/Serviço','ISO-8859-1', 'UTF-8'),
        "Qtd",
        "Medida",
        "Valor Unit.",
        "Valor Total",
        "Frete",
        mb_convert_encoding('Nº NF','ISO-8859-1', 'UTF-8'),
        "Solicitante",
        mb_convert_encoding('Situação','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Data Aprovação','ISO-8859-1', 'UTF-8'),
        "Obs."
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) ,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


