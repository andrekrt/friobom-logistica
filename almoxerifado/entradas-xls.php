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
    $sql = $db->query("SELECT identrada_estoque, data_nf, num_nf, num_pedido, CONCAT(idpeca, ' - ', descricao_peca) as peca, preco_custo, qtd, desconto, obs, apelido, vl_total_comprado FROM `entrada_estoque` LEFT JOIN peca_estoque ON entrada_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON entrada_estoque.id_usuario = usuarios.idusuarios LEFT JOIN fornecedores ON entrada_estoque.fornecedor = fornecedores.id");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=entradas.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "ID",
        "Data NF",
        mb_convert_encoding('Nº NF','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Nº Pedido','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Peça','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Preço','ISO-8859-1', 'UTF-8'),
        "Quantidade",
        "Desconto",
        mb_convert_encoding('Observações','ISO-8859-1', 'UTF-8'),
        "Fornecedor",
        "Valor Total"
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) , 'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


