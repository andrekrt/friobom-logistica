<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] != 3 ){

    $db->exec("set names utf8");
    $lang = $db->query("SET lc_time_names = 'pt_PT'");
    $sql = $db->query("SELECT token, data_atual, monthname(data_atual), YEAR(data_atual), placa, veiculos.categoria as categoriaVeiculo, motorista, rota, problema, local_reparo, peca_reparo.categoria as categoriaPeca, descricao, qtd, un_medida, vl_unit, vl_total, frete, nome_usuario, situacao, data_aprovacao, obs FROM solicitacoes_new LEFT JOIN peca_reparo ON solicitacoes_new.peca_servico = peca_reparo.id_peca_reparo LEFT JOIN usuarios ON solicitacoes_new.usuario = usuarios.idusuarios LEFT JOIN veiculos ON solicitacoes_new.placa = veiculos.placa_veiculo");

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
        "Local Reparo",
        mb_convert_encoding('Categoria Peça','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Peça/Serviço','ISO-8859-1', 'UTF-8'),
        "Qtd",
        "Medida",
        "Valor Unit.",
        "Valor Total",
        "Frete",
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


