<?php

session_start();
require("../conexao.php");
$idModudulo = 8;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
    $idUsuario = $_SESSION['idUsuario'];
    $carga = filter_input(INPUT_POST, 'carga');
    $pedido = filter_input(INPUT_POST,'pedido');
    $situacao = "Aguardando Confirmação";

    $inserir = $db->prepare("INSERT INTO denegadas (carga, pedido, situacao, usuario) VALUES (:carga, :pedido, :situacao, :usuario)");
    $inserir->bindValue(':carga', $carga);
    $inserir->bindValue(':pedido', $pedido);
    $inserir->bindValue(':situacao', $situacao);
    $inserir->bindValue(':usuario', $idUsuario);

    if($inserir->execute()){
        echo "<script>alert('NF Denegada Registrada!');</script>";
        echo "<script>window.location.href='denegadas.php'</script>";    
        
    }else{
        print_r($inserir->errorInfo());
    }

}

?>