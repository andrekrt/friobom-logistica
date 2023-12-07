<?php

session_start();
require("../conexao.php");

$idModudulo = 14;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $db->exec("set names utf8");
    $sql = $db->query("SELECT idrotas, saida, nome_supervisor, placa_veiculo, chegada, velocidade_max, qtd_visitas , rca01, rca02, cidades, hora_almoco, obs, nome_usuario FROM rotas_supervisores rotas_supervisores LEFT JOIN supervisores ON rotas_supervisores.supervisor = supervisores.idsupervisor LEFT JOIN veiculos ON supervisores.veiculo = veiculos.cod_interno_veiculo LEFT JOIN usuarios ON rotas_supervisores.usuario = usuarios.idusuarios");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=rotas-supervisores.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        mb_convert_encoding('Data de Saída','ISO-8859-1', 'UTF-8'),
        "Supervisor",
        "Placa",
        "Data Chegada",
        mb_convert_encoding('Velocidade Máxima','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Nº de Visitas','ISO-8859-1', 'UTF-8'),
        "RCA 1",
        "RCA 2",
        "Cidades",
        mb_convert_encoding('Horas de Almoço','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Obs.','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Nome Usuário','ISO-8859-1', 'UTF-8')
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fwrite($arquivo,
        date("d/m/Y", strtotime($dado['saida'])) .";".  mb_convert_encoding($dado['nome_supervisor'],'ISO-8859-1', 'UTF-8').";".$dado['placa_veiculo']. ";".date("d/m/Y", strtotime($dado['chegada'])). ";". $dado['velocidade_max'].";" .mb_convert_encoding($dado['qtd_visitas'],'ISO-8859-1', 'UTF-8').";".$dado['rca01'].";".$dado['rca02'].";".mb_convert_encoding($dado['cidades'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['hora_almoco'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['obs'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['nome_usuario'],'ISO-8859-1', 'UTF-8')."\n"
        );
    }

    fclose($arquivo);
}


