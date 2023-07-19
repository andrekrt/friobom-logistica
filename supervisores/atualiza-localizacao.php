<?php

session_start();
require("../conexao.php");

$idModudulo = 14;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = filter_input(INPUT_POST, 'id');
    $cliente = filter_input(INPUT_POST, 'cliente');
    $endereco = filter_input(INPUT_POST, 'endereco');
    $bairro = filter_input(INPUT_POST, 'bairro');
    $cidade = filter_input(INPUT_POST, 'cidade');
    $situacao = filter_input(INPUT_POST, 'situacao');  

    $sql = $db->prepare("UPDATE localizacao SET endereco = :endereco, bairro = :bairro, cidade = :cidade, situacao = :situacao WHERE id = :id");
    $sql->bindValue(':endereco', $endereco);
    $sql->bindValue(':bairro', $bairro);
    $sql->bindValue(':cidade', $cidade);
    $sql->bindValue(':situacao', $situacao);
    $sql->bindValue(':id', $id);
    
    if($sql->execute()){
        echo "<script> alert('Visita Atualizada')</script>";
        echo "<script> window.location.href='geolocalizacao.php' </script>";  
    }else{
        print_r($sql->errorInfo());
    }
    

}else{

}

?>