<?php

session_start();
require("../conexao-on.php");

$idModudulo = 13;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $db->exec("set names utf8");
    $sql = $db->query("SELECT idcombustivel_saida, data_abastecimento, litro_abastecimento, preco_medio, valor_total, carregamento, km, placa_veiculo, rota, motorista, tipo_abastecimento, nome_usuario FROM combustivel_saida LEFT JOIN usuarios ON combustivel_saida.usuario = usuarios.idusuarios");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=saidas.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "ID",
        "Data Abastecimento",
        "Litros Abastecimento",
        mb_convert_encoding('R$/Lt','ISO-8859-1', 'UTF-8'),
        "Valor Total",
        "Carregamento",
        "KM",
        "Placa",
        "Rota",
        "Motorista",
        "Tipo de Abastecimento",
        mb_convert_encoding('Usuário que Lançou','ISO-8859-1', 'UTF-8')
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, mb_convert_encoding($dado,'ISO-8859-1', 'UTF-8') , ';');
    }

    fclose($arquivo);
}


