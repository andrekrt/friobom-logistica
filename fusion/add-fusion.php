<?php

use Mpdf\Tag\Input;

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && ($_SESSION['tipoUsuario']==2 || $_SESSION['tipoUsuario']==99 || $_SESSION['tipoUsuario']==1)){

    $saida = filter_input(INPUT_POST, 'saida');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $rota = filter_input(INPUT_POST, 'rota');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $entregas = filter_input(INPUT_POST, 'entregas');
    $situacao = "Pendente";
    $usuario = $_SESSION['idUsuario'];

    //verificar se já existe o carregamento
    $sqlConsulta = $db->prepare("SELECT carregamento FROM fusion WHERE carregamento=:carregamento");
    $sqlConsulta->bindValue(':carregamento', $carregamento);
    $sqlConsulta->execute();
    if($sqlConsulta->rowCount()>0){
        echo "<script> alert('Esse carregamento já está registrado!')</script>";
        echo "<script> window.location.href='fusion.php' </script>";
    }else{
        $sql = $db->prepare("INSERT INTO fusion (saida, carregamento, veiculo, rota, motorista, num_entregas, situacao, usuario) VALUES (:saida, :carregamento, :veiculo, :rota, :motorista, :entregas, :situacao, :usuario)");
        $sql->bindValue(':saida', $saida);
        $sql->bindValue(':carregamento', $carregamento);
        $sql->bindValue(':veiculo', $veiculo);
        $sql->bindValue(':rota', $rota);
        $sql->bindValue(':motorista', $motorista);
        $sql->bindValue(':entregas', $entregas);
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':usuario', $usuario);
        if($sql->execute()){
            echo "<script> alert('Lançado!')</script>";
            echo "<script> window.location.href='fusion.php' </script>";
        }else{
            print_r($sql->errorInfo());
        }    
    }
    
    

}else{
    echo "<script> alert('Acesso não permitido!!!')</script>";
    echo "<script> window.location.href='../index.php' </script>";
}

?>