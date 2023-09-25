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
    $sql = $db->query("SELECT idsaida_estoque, data_saida, qtd, CONCAT(id_peca_reparo, ' - ', descricao) as peca, valor_total/total_estoque as valorMedio, solicitante, placa, obs, nome_usuario FROM `saida_estoque` LEFT JOIN peca_reparo ON saida_estoque.peca_idpeca = peca_reparo.id_peca_reparo LEFT JOIN usuarios ON saida_estoque.id_usuario = usuarios.idusuarios");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=saidas.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "ID",
        mb_convert_encoding('Data Saída','ISO-8859-1', 'UTF-8'),
        "Qtd",
        mb_convert_encoding('Peça','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Valor Médio','ISO-8859-1', 'UTF-8'),
        "Solicitante",
        "Placa",
        mb_convert_encoding('Observações','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Saída','ISO-8859-1', 'UTF-8')
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) , 'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


