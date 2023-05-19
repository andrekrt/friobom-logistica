<?php

session_start();
require("../../conexao.php");

if($_SESSION['tipoUsuario']==2 || $_SESSION['tipoUsuario']==99 || $_SESSION['tipoUsuario']==1){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT * FROM fusion_praca LEFT JOIN veiculos ON fusion_praca.veiculo = veiculos.cod_interno_veiculo LEFT JOIN auxiliares_rota ON fusion_praca.ajudante = auxiliares_rota.idauxiliares LEFT JOIN rotas ON fusion_praca.rota = rotas.cod_rota LEFT JOIN usuarios ON fusion_praca.usuario = usuarios.idusuarios");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=fusion-praca.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        mb_convert_encoding('Data de Saída','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Data de Finalização','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Data de Chegada','ISO-8859-1', 'UTF-8'),
        "Carregamento",
        mb_convert_encoding('Placa Veículo','ISO-8859-1', 'UTF-8'),
        "Ajudante",
        "Rota",
        mb_convert_encoding('Nº de Entregas','ISO-8859-1', 'UTF-8'),
        "Entregas Realizadass",
        mb_convert_encoding('Nº de Devoluções','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Entregas Líquidas','ISO-8859-1', 'UTF-8'),
        "Erros no Fusion",
        mb_convert_encoding('Nº Devoluções sem Avisar','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Devolução','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Entregas','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Prestação de Contas','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Dias em Rota','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Prêmio Máximo','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Prêmio Pago','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Prêmio','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Situação','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fwrite($arquivo,
        date("d/m/Y", strtotime($dado['data_saida'])) .";".   date("d/m/Y H:i", strtotime($dado['data_finalizacao'])).";". date("d/m/Y H:i", strtotime($dado['data_chegada'])). ";".$dado['carga']. ";". $dado['placa_veiculo'].";" .mb_convert_encoding($dado['nome_auxiliar'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['nome_rota'],'ISO-8859-1', 'UTF-8').";".$dado['num_entregas'].";".$dado['entregas_ok'].";".$dado['num_devolucao'].";".$dado['entregas_liq'].";".$dado['num_uso_incorreto'].";".$dado['devolucao_sem_aviso'].";".$dado['perc_devolucao'].";".$dado['perc_entregas'].";".$dado['perc_contas'].";".$dado['perc_rota'].";".number_format($dado['premio_possivel'],2,",",".").";".number_format($dado['premio_real'],2,",",".").";".number_format($dado['perc_premio'],2,",","."). ";".$dado['situacao']. "\n"
        );
    }

    fclose($arquivo);
}


