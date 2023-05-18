<?php

session_start();
require("../../conexao.php");

if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $db->exec("set names utf8");
    $sql = $db->query("SELECT data_cadastro, data_medicao, num_fogo, medida, calibragem_maxima, marca, num_serie, sucos.vida, sucos.veiculo, km_veiculo, km_pneu, carcaca, posicao_inicio, sucos.suco01 as medida01, sucos.suco02 as medida02, sucos.suco03 as medida03, sucos.suco04 as medida04 , pneus.suco01 as cadastro01, pneus.suco02 as cadastro02, pneus.suco03 as cadastro03, pneus.suco04 as cadastro04,  calibragem, nome_usuario FROM sucos LEFT JOIN pneus ON sucos.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON sucos.usuario = usuarios.idusuarios");

    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition: attachement; filename=suco.csv');

    $arquivo = fopen("php://output", "w");

    $cabacelho = [
        "Data de Cadastro",
        mb_convert_encoding('Data de Medição','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Nº Fogo','ISO-8859-1', 'UTF-8'),
        "Medida",
        mb_convert_encoding('Calibragem Máxima','ISO-8859-1', 'UTF-8'),
        "Marca",
        mb_convert_encoding('Nº Série','ISO-8859-1', 'UTF-8'),
        "Vida",
        mb_convert_encoding('Veículo','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Km Veículo','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Km Pneu','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Carcaça','ISO-8859-1', 'UTF-8'),
        mb_convert_encoding('Posição','ISO-8859-1', 'UTF-8'),
        "Suco de 01 de Cadastro",
        "Suco de 01 de Medida",
        "Suco de 02 de Cadastro",
        "Suco de 02 de Medida",
        "Suco de 03 de Cadastro",
        "Suco de 03 de Medida",
        "Suco de 04 de Cadastro",
        "Suco de 04 de Medida",
        "Calibragem Encontrada",
        "Cadastrado"
    ];
    
    fputcsv($arquivo, $cabacelho, ';');

    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach($dados as $dado){
        fputcsv($arquivo, $dado, ';');
    }

    fclose($arquivo);
}


