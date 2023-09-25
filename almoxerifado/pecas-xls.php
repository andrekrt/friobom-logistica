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

    // $db->exec("set names utf8");
    $sql = $db->prepare("SELECT id_peca_reparo, descricao, un_medida, categoria, estoque_minimo, total_entrada, total_saida, total_estoque, valor_total, situacao, nome_usuario FROM peca_reparo LEFT JOIN usuarios ON peca_reparo.usuario = usuarios.idusuarios");
    $sql->execute();

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=estoque.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "ID",
        mb_convert_encoding('Descrição Peça','ISO-8859-1', 'UTF-8'),
        "Medida",
        "Grupo",
        mb_convert_encoding('Estoque Mínimo','ISO-8859-1', 'UTF-8'),
        "Total Entrada",
        mb_convert_encoding('Total Saída','ISO-8859-1', 'UTF-8'),
        "Total Estoque",
        "Total Comprado",
        mb_convert_encoding('Situação','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Usuário que Cadastrou','ISO-8859-1', 'UTF-8')
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) , 'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


