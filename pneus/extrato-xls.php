<?php

session_start();
require("../conexao.php");

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $db->exec("set names utf8");
    $sql = $db->query("SELECT * FROM  extrato_pneu LEFT JOIN pneus ON extrato_pneu.pneu = pneus.idpneus");
    $dados = $sql->fetchAll();

    $fp = fopen("extrato.csv", "w");
    $escreve = fwrite($fp, "ID;". mb_convert_encoding('Nº Fogo', 'ISO-8859-1', 'UTF-8') .";". mb_convert_encoding('Data Operação','ISO-8859-1', 'UTF-8') .";". mb_convert_encoding('Operação', 'ISO-8859-1', 'UTF-8') ."; Km Pneu;".mb_convert_encoding('Veículo','ISO-8859-1', 'UTF-8') ." ;".mb_convert_encoding('Km Veículo','ISO-8859-1', 'UTF-8'));

    foreach($dados as $dado){
        $escreve=fwrite($fp,
            "\n".$dado['idextrato'].";" . $dado['num_fogo'] .";". date("d/m/Y",strtotime($dado['data_op'])).";". mb_convert_encoding($dado['operacao'],'ISO-8859-1', 'UTF-8') .";".  $dado['km_pneu'] ."; $dado[veiculo]; $dado[km_veiculo];". ""
        );
    }

    fclose($fp);

    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=extrato.csv");
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");

    // Read the file
    readfile('extrato.csv');
    exit;

}


