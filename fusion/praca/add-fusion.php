<?php
session_start();
require("../../conexao.php");

$idModudulo = 16;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $saida = filter_input(INPUT_POST, 'saida');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $rota = filter_input(INPUT_POST, 'rota');
    $motorista = filter_input(INPUT_POST, 'ajudante');
    $entregas = filter_input(INPUT_POST, 'entregas');
    $usuario = $_SESSION['idUsuario'];

    //verificar se já existe o carregamento
    $sqlConsulta = $db->prepare("SELECT carregamento FROM fusion_praca WHERE carga=:carregamento");
    $sqlConsulta->bindValue(':carregamento', $carregamento);
    $sqlConsulta->execute();
    if($sqlConsulta->rowCount()>0){
        echo "<script> alert('Esse carregamento já está registrado!')</script>";
        echo "<script> window.location.href='fusion.php' </script>";
    }else{
        $sql = $db->prepare("INSERT INTO fusion_praca (data_saida, carga, veiculo, rota, ajudante, num_entregas, usuario) VALUES (:saida, :carregamento, :veiculo, :rota, :ajudante, :entregas, :usuario)");
        $sql->bindValue(':saida', $saida);
        $sql->bindValue(':carregamento', $carregamento);
        $sql->bindValue(':veiculo', $veiculo);
        $sql->bindValue(':rota', $rota);
        $sql->bindValue(':ajudante', $motorista);
        $sql->bindValue(':entregas', $entregas);
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