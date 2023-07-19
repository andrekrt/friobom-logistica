<?php

session_start();
require("../conexao.php");

$idModudulo = 3;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $codRota = filter_input(INPUT_POST, 'codRota');
    $rota = filter_input(INPUT_POST, 'nomeRota');
    $horaFechamento1 = filter_input(INPUT_POST, 'horaFechamento1');
    $horaFechamento2 = filter_input(INPUT_POST, 'horaFechamento2');
    $ceps = filter_input(INPUT_POST, 'ceps');
    $fechamento1 = filter_input(INPUT_POST, 'fechamento1');
    $fechamento2 = filter_input(INPUT_POST, 'fechamento2');
    $metaDias = str_replace(",",".", filter_input(INPUT_POST, 'metaDias'));

    $atualiza = $db->prepare("UPDATE rotas SET nome_rota = :rota, fechamento1 = :fechamento1, fechamento2 = :fechamento2, hora_fechamento1 = :horaFechamento1, hora_fechamento2 = :horaFechamento2, ceps = :ceps, meta_dias = :metaDias WHERE cod_rota = :codRota");
    $atualiza->bindValue(':codRota',$codRota);
    $atualiza->bindValue(':rota', $rota);
    $atualiza->bindValue(':ceps', $ceps);
    $atualiza->bindValue(':horaFechamento1', $horaFechamento1);
    $atualiza->bindValue(':horaFechamento2', $horaFechamento2);
    $atualiza->bindValue(':fechamento1', $fechamento1);
    $atualiza->bindValue(':fechamento2', $fechamento2);
    $atualiza->bindValue(':metaDias', $metaDias);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='rotas.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    header("Location:rotas.php");
}

?>