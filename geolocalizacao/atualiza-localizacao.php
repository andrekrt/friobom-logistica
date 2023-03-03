<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario']==2 || $_SESSION['tipoUsuario']==99){

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