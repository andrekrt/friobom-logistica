<?php

session_start();
require("../conexao.php");

$db->exec("set names utf8");
$sql = $db->query("SELECT * FROM combustivel_saida LEFT JOIN usuarios ON combustivel_saida.usuario = usuarios.idusuarios");
$dados = $sql->fetchAll();

$fp = fopen("abastecimento.csv", "w");
$escreve = fwrite($fp, "ID; Data Abastecimento; Litros Abastecimento; R$/Lt; Valor Total; Carregamento; Km; Placa; Rota; Motorista; Tipo Abastecimento;". utf8_decode('Usuário que Lançou') );

foreach($dados as $dado){
    $escreve=fwrite($fp,
        "\n$dado[idcombustivel_saida];". date("d/m/Y", strtotime($dado['data_abastecimento'])). ";".number_format($dado['litro_abastecimento'],2,",","."). ";". number_format($dado['preco_medio'],2,",",".") .";". number_format($dado['valor_total'],2,",",".") ."; $dado[carregamento]; $dado[km]; $dado[placa_veiculo];". utf8_decode($dado['rota']).";". utf8_decode($dado['motorista']) .";". utf8_decode($dado['tipo_abastecimento']).";". utf8_decode($dado['nome_usuario'])
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

