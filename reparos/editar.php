<?php

session_start();
require("../conexao.php");

$idSolicitacao = filter_input(INPUT_GET, 'id');

if(isset($idSolicitacao) && empty($idSolicitacao) == false ){
    $id=$_SESSION['idUsuario'];  
    
    $sql = $db->query("DELETE FROM solicitacoes WHERE id = '$idSolicitacao'");
    if($sql){
        echo "<script>alert('Excluído com Sucesso!');</script>";
        echo "<script>window.location.href='solicitacoes.php'</script>";
    }else{
        echo "erro";
    }
    
    
}else{
    header("Location:solicitacoes.php");
}

?>