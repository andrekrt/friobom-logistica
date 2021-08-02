<?php

session_start();
require("../conexao.php");

$token = filter_input(INPUT_GET, 'token');

if(isset($token) && empty($token) == false ){
    $id=$_SESSION['idUsuario'];  
    
    $sql = $db->prepare("DELETE FROM solicitacoes_new WHERE token = :token");
    $sql->bindValue(':token',$token);
    if($sql->execute()){
        echo "<script>alert('Excluído com Sucesso!');</script>";
        echo "<script>window.location.href='solicitacoes.php'</script>";
    }else{
        echo "erro";
    }
    
    
}else{
    header("Location:solicitacoes.php");
}

?>