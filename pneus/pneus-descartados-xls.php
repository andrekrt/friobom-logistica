<?php

session_start();
require("../conexao.php");

if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT * FROM pneus LEFT JOIN usuarios ON pneus.usuario = usuarios.idusuarios WHERE uso = 0");
    $dados = $sql->fetchAll();

    $fp = fopen("pneus-descartados.csv", "w");
    $escreve = fwrite($fp, "Data de Cadastro;". mb_convert_encoding('Nº Fogo', 'ISO-8859-1', 'UTF-8') ."; Medida;". mb_convert_encoding('Calibragem Máxima','ISO-8859-1', 'UTF-8') ."; Marca; Modelo;". mb_convert_encoding('Nº de Série', 'ISO-8859-1', 'UTF-8') ."; Vida;".mb_convert_encoding('Posição Início','ISO-8859-1', 'UTF-8') ." ;".mb_convert_encoding('Situação','ISO-8859-1', 'UTF-8')." ;".mb_convert_encoding('Localização', 'ISO-8859-1', 'UTF-8')." ;". mb_convert_encoding('Veículo Atual','ISO-8859-1', 'UTF-8').";" .mb_convert_encoding('Km Inicial Veículo', 'ISO-8859-1', 'UTF-8').";". mb_convert_encoding('Km Final Veículo', 'ISO-8859-1', 'UTF-8').";Km Rodado; Suco 01; Suco 02; Suco 03; Suco 04; Motivo Descarte");

    foreach($dados as $dado){
        if($dado['uso']==1){
            $uso = 'SIM';
        }elseif($dado['uso']==0){
            $uso="NÃO";
        }
        $escreve=fwrite($fp,
            "\n". date("d/m/Y",strtotime($dado['data_cadastro'])).";". $dado['num_fogo']. ";".$dado['medida']. ";". $dado['calibragem_maxima'] .";". mb_convert_encoding($dado['marca'],'ISO-8859-1', 'UTF-8') .";".  mb_convert_encoding($dado['modelo'],'ISO-8859-1', 'UTF-8') ."; $dado[num_serie]; $dado[vida];". ($dado['posicao_inicio']).";". mb_convert_encoding($dado['situacao'],'ISO-8859-1', 'UTF-8') .";". ($dado['localizacao']).";". ($dado['veiculo']."; $dado[km_inicial];$dado[km_final]; $dado[km_rodado]; $dado[suco01]; $dado[suco02]; $dado[suco03]; $dado[suco04];". mb_convert_encoding($dado['motivo_descarte'],'ISO-8859-1', 'UTF-8'). "")
        );
    }

    fclose($fp);

    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=pneus-descartados.csv");
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");

    // Read the file
    readfile('pneus-descartados.csv');
    exit;

}


