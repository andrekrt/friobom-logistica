<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario']==2 || $_SESSION['tipoUsuario']==99){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT saida, nome_supervisor, placa_veiculo, chegada, velocidade_max, cidade1.nome_cidade as cidade_saida, rca01, rca02, obs, cidade2.nome_cidade as cidade_final, diarias, qtd_visitas, cidade0.nome_cidade as cidade_residencia, nome_usuario FROM rotas_supervisores rotas_supervisores LEFT JOIN supervisores ON rotas_supervisores.supervisor = supervisores.idsupervisor LEFT JOIN veiculos ON rotas_supervisores.veiculo = veiculos.cod_interno_veiculo LEFT JOIN cidades cidade1 ON rotas_supervisores.cidade_inicio = cidade1.idcidades LEFT JOIN cidades cidade2 ON rotas_supervisores.cidade_final = cidade2.idcidades LEFT JOIN cidades cidade0 ON rotas_supervisores.residencia = cidade0.idcidades LEFT JOIN usuarios ON rotas_supervisores.usuario = usuarios.idusuarios");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=rotas-supervisores.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        mb_convert_encoding('Data de Saída','ISO-8859-1', 'UTF-8'),
        "Supervisor",
        "Placa",
        "Data Chegada",
        mb_convert_encoding('Velocidade Máxima','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Cidade de Inicío','ISO-8859-1', 'UTF-8'),
        "RCA 1",
        "RCA 2",
        "Cidade/Obs",
        "Cidade Final",
        mb_convert_encoding('Diárias','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Nº de Visitas','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Residência','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Nome Usuário','ISO-8859-1', 'UTF-8')
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fwrite($arquivo,
        date("d/m/Y", strtotime($dado['saida'])) .";".  mb_convert_encoding($dado['nome_supervisor'],'ISO-8859-1', 'UTF-8').";".$dado['placa_veiculo']. ";".date("d/m/Y", strtotime($dado['chegada'])). ";". $dado['velocidade_max'].";" .mb_convert_encoding($dado['cidade_saida'],'ISO-8859-1', 'UTF-8').";".$dado['rca01'].";".$dado['rca02'].";".mb_convert_encoding($dado['obs'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['cidade_final'],'ISO-8859-1', 'UTF-8').";".number_format($dado['diarias'],1,",",".").";".$dado['qtd_visitas'].";".mb_convert_encoding($dado['cidade_residencia'],'ISO-8859-1', 'UTF-8').";".$dado['nome_usuario']."\n"
        );
    }

    fclose($arquivo);
}


