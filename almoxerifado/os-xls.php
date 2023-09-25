<?php

session_start();
require("../conexao.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $db->exec("set names utf8");
    $sql = $db->query("SELECT * FROM ordem_servico LEFT JOIN usuarios ON ordem_servico.idusuario = usuarios.idusuarios");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=os.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "ID",
        "Data de Abertura",
        "Placa",
        mb_convert_encoding('Descrição Problema','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Tipo de Manutenção','ISO-8859-1', 'UTF-8'),
        "Corretiva",
        "Preventiva",
        mb_convert_encoding('Manutenção Externa','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Troca de Óleo','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Higienização','ISO-8859-1', 'UTF-8'),
        "Agente Causador",
        mb_convert_encoding('Nº Requisição','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Nº Solicitação','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Nº NF','ISO-8859-1', 'UTF-8'),
        "Obs",
        mb_convert_encoding('Situação','ISO-8859-1', 'UTF-8'),
        "Data Encerramento",
        mb_convert_encoding('Lançado por','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        
        $corretiva = $dado['corretiva']?"SIM":mb_convert_encoding("NÃO",'ISO-8859-1', 'UTF-8');
        $preventiva = $dado['preventiva']?"SIM":mb_convert_encoding("NÃO",'ISO-8859-1', 'UTF-8');
        $externa = $dado['externa']?"SIM":mb_convert_encoding("NÃO",'ISO-8859-1', 'UTF-8');
        $oleo = $dado['oleo']?"SIM":mb_convert_encoding("NÃO",'ISO-8859-1', 'UTF-8');
        $higienizacao = $dado['higienizacao']?"SIM":mb_convert_encoding("NÃO",'ISO-8859-1', 'UTF-8');

        fwrite($arquivo,
            "$dado[idordem_servico];". date("d/m/Y",strtotime($dado['data_abertura'])). "; $dado[placa];" . mb_convert_encoding($dado['descricao_problema'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['tipo_manutencao'],'ISO-8859-1', 'UTF-8')."; $corretiva; $preventiva; $externa; $oleo; $higienizacao;". mb_convert_encoding($dado['causador'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['requisicao_saida'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['solicitacao_peca'],'ISO-8859-1', 'UTF-8'). ";$dado[num_nf];" . mb_convert_encoding($dado['obs'],'ISO-8859-1', 'UTF-8'). ";" . mb_convert_encoding($dado['situacao'],'ISO-8859-1', 'UTF-8').";".date("d/m/mY",strtotime($dado['data_encerramento'])).";".mb_convert_encoding($dado['nome_usuario'],'ISO-8859-1', 'UTF-8')."\n"
        );
    }

    fclose($arquivo);
}


