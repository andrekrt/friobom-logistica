<?php

session_start();
require("../conexao.php");

$db->exec("set names utf8");
$sql = $db->query("SELECT * FROM combustivel_saida LEFT JOIN usuarios ON combustivel_saida.usuario = usuarios.idusuarios");
$dados = $sql->fetchAll();

$fp = fopen("abastecimento.csv", "w");
$escreve = fwrite($fp, "ID; Data Abastecimento; Litros Abastecimento; R$/Lt; Valor Total; Carregamento; Km; Placa; Rota; Motorista; Tipo Abastecimento;". mb_convert_encoding('Situação','ISO-8859-1', 'UTF-8') ).";" . mb_convert_encoding('Usuário que Lançou','ISO-8859-1', 'UTF-8') ;

foreach($dados as $dado){
    $rota = $dado['rota']?mb_convert_encoding($dado['rota'],'ISO-8859-1', 'UTF-8'):"";
    $motorista = $dado['motorista']? mb_convert_encoding($dado['motorista'],'ISO-8859-1', 'UTF-8'):"";
    $tipo= $dado['tipo_abastecimento']?mb_convert_encoding($dado['tipo_abastecimento'],'ISO-8859-1', 'UTF-8'):"";
    $usuario = $dado['usuario']?mb_convert_encoding($dado['nome_usuario'],'ISO-8859-1', 'UTF-8'):"";
    
    $escreve=fwrite($fp,
        "\n$dado[idcombustivel_saida];". date("d/m/Y", strtotime($dado['data_abastecimento'])). ";".number_format($dado['litro_abastecimento'],2,",","."). ";". number_format($dado['preco_medio'],2,",",".") .";". number_format($dado['valor_total'],2,",",".") ."; $dado[carregamento]; $dado[km]; $dado[placa_veiculo];".$rota.";". $motorista .";".$tipo .";$dado[situacao];".  $usuario
    );
}

fclose($fp);

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=abastecimento.csv");
header("Content-Type: application/zip");
header("Content-Transfer-Encoding: binary");

// Read the file
readfile('abastecimento.csv');
exit;

