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
    $filial = $_SESSION['filial'];
    if($filial===99){
        $condicao = " ";
    }else{
        $condicao = " WHERE inventario_almoxarifado.filial=$filial";
    }

    $db->exec("set names utf8");
    $sql = $db->query("SELECT inventario_almoxarifado.filial,idinventario, data_inv, id_peca_reparo, descricao, categoria, un_medida, qtd, nome_usuario FROM inventario_almoxarifado LEFT JOIN usuarios ON inventario_almoxarifado.usuario = usuarios.idusuarios LEFT JOIN peca_reparo ON inventario_almoxarifado.peca = peca_reparo.id_peca_reparo $condicao");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=inventario.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Filial",
        "ID",
        "Data",
        mb_convert_encoding('Código Peça','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Peça','ISO-8859-1', 'UTF-8'),
        "Grupo",
        "Medida",
        "Qtd",
        mb_convert_encoding('Lançado','ISO-8859-1', 'UTF-8'),
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding(str_replace(".",",",$dado) , 'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


