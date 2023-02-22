<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] != 4){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT * FROM veiculos");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=veiculos.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        mb_convert_encoding('Código','ISO-8859-1', 'UTF-8'),
        "Tipo",
        "Categoria",
        "Placa",
        mb_convert_encoding('Peso Máximo','ISO-8859-1', 'UTF-8'),
        "Cubagem",
        mb_convert_encoding('Data Revisão Óleo','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Revisão Óleo(Km)','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Data Revisão Diferencial','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Revisão Diferencial(Km)','ISO-8859-1', 'UTF-8'),
        "Km Atual",
        mb_convert_encoding('Km Restante de Revisão','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Status Revisão','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Km Último Alinhamento','ISO-8859-1', 'UTF-8'),
        "Status Alinhamento",
        "Ativo"
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){

        $kmRestante = $dado['km_atual']-$dado['km_ultima_revisao'];
        $kmRestanteAlinhamento = $dado['km_atual']-$dado['km_alinhamento'];
        if ($dado['categoria']=='Truck' && $kmRestante >= 20000) {
            $situacao = "Pronto para Revisão";
        } elseif($dado['categoria']=='Toco' && $kmRestante >= 20000) {
            $situacao = "Pronto para Revisão";
        }elseif($dado['categoria']=='Mercedinha' && $kmRestante >= 15000){
            $situacao = "Pronto para Revisão";
        }else{
            $situacao = "Aguardando";
        }
            //situacao alinhamento
        if($kmRestanteAlinhamento>=7000){
            $situacaoAlinhamento = 'Pronto para Alinhamento';
        }else{
            $situacaoAlinhamento = 'Aguardando';
        }

        if($dado['ativo']==0){
            $ativo = "NÃO";
        }else{
            $ativo = "SIM";
        }

        fwrite($arquivo, 
            "\n$dado[cod_interno_veiculo];".mb_convert_encoding($dado['tipo_veiculo'],'ISO-8859-1', 'UTF-8').";".mb_convert_encoding($dado['categoria'],'ISO-8859-1', 'UTF-8')."; $dado[placa_veiculo]; $dado[peso_maximo]; $dado[cubagem];". date("d/m/Y", strtotime($dado['data_revisao_oleo']))."; $dado[km_ultima_revisao];".date("d/m/Y", strtotime($dado['data_revisao_diferencial']))."; $dado[km_revisao_diferencial]; $dado[km_atual]; $kmRestante;". mb_convert_encoding($situacao,'ISO-8859-1', 'UTF-8' )."; $kmRestanteAlinhamento;". mb_convert_encoding($situacaoAlinhamento,'ISO-8859-1', 'UTF-8' ).";".mb_convert_encoding($ativo,'ISO-8859-1', 'UTF-8' )
        );

        // fputcsv($arquivo, mb_convert_encoding($dado,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


