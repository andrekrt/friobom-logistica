<?php

session_start();
require("../conexao.php");

$idModudulo = 16;
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
        $condicao = "AND fusion.filial=$filial";
    }

    $db->exec("set names utf8");
    $sql = $db->query("SELECT * FROM fusion LEFT JOIN veiculos ON fusion.veiculo = veiculos.cod_interno_veiculo LEFT JOIN motoristas ON fusion.motorista = motoristas.cod_interno_motorista LEFT JOIN rotas ON fusion.rota = rotas.cod_rota LEFT JOIN usuarios ON fusion.usuario = usuarios.idusuarios WHERE 1 $condicao");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=fusion.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Filial",
        mb_convert_encoding('Data de Saída','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Data de Finalização','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Data de Chegada','ISO-8859-1', 'UTF-8'),
        "Carregamento",
        mb_convert_encoding('Placa Veículo','ISO-8859-1', 'UTF-8'),
        "Motorista",
        "Rota",
        mb_convert_encoding('Nº de Entregas','ISO-8859-1', 'UTF-8'),
        "Entregas Realizadass",
        "Erros no Fusion",
        mb_convert_encoding('Nº de Devoluções','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Entregas Líquidas','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Uso Fusion','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Check-List','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Média de Consumo','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Devolução','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Dias em Rota','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Velocidade Máxima','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Prêmio Máximo','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Prêmio Alcançado','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('% Prêmio','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fwrite($arquivo,
        $dado['fillial'].";". date("d/m/Y H:i", strtotime($dado['saida'])) .";".   date("d/m/Y H:i", strtotime($dado['termino_rota'])).";". date("d/m/Y H:i", strtotime($dado['chegada_empresa'])). ";".$dado['carregamento']. ";". $dado['placa_veiculo'].";" .mb_convert_encoding($dado['nome_motorista'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['nome_rota'],'ISO-8859-1', 'UTF-8').";".$dado['num_entregas'].";".$dado['entregas_feitas'].";".$dado['erros_fusion'].";".$dado['num_dev'].";".$dado['entregas_liq'].";".$dado['uso_fusion'].";".$dado['checklist'].";".$dado['media_km'].";".$dado['devolucao'].";".$dado['dias_rota'].";".$dado['vel_max'].";".number_format($dado['premio_possivel'],2,",",".").";".number_format($dado['premio_real'],2,",",".").";".number_format($dado['premio_alcancado'],2,",",".")."\n"
        );
    }

    fclose($arquivo);
}


