<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] ==99){

    $codRota = filter_input(INPUT_POST, 'codRota');
    $rota = filter_input(INPUT_POST, 'nomeRota');
    $horaFechamento1 = filter_input(INPUT_POST, 'horaFechamento1');
    $horaFechamento2 = filter_input(INPUT_POST, 'horaFechamento2');
    $ceps = filter_input(INPUT_POST, 'ceps');
    $fechamento1 = filter_input(INPUT_POST, 'fechamento1');
    $fechamento2 = filter_input(INPUT_POST, 'fechamento2');

    $atualiza = $db->prepare("UPDATE rotas SET nome_rota = :rota, fechamento1 = :fechamento1, fechamento2 = :fechamento2, hora_fechamento1 = :horaFechamento1, hora_fechamento2 = :horaFechamento2, ceps = :ceps WHERE cod_rota = :codRota");
    $atualiza->bindValue(':codRota',$codRota);
    $atualiza->bindValue(':rota', $rota);
    $atualiza->bindValue(':ceps', $ceps);
    $atualiza->bindValue(':horaFechamento1', $horaFechamento1);
    $atualiza->bindValue(':horaFechamento2', $horaFechamento2);
    $atualiza->bindValue(':fechamento1', $fechamento1);
    $atualiza->bindValue(':fechamento2', $fechamento2);

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