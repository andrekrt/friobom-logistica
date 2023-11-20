<?php

session_start();
require("../conexao.php");

$id = filter_input(INPUT_GET, 'id');

if(isset($id) && empty($id) == false ){ 
    
    $sql = $db->prepare("UPDATE auxiliares_rota SET ativo=0 WHERE idauxiliares=:id ");
    $sql->bindValue(':id',$id);
    
    if($sql->execute()){
        echo "<script>alert('Desativado com Sucesso!');</script>";
        echo "<script>window.location.href='auxiliares.php'</script>";
    }else{
        print_r($sql->errorInfo());
    }
    
}else{
    header("Location:solicitacoes.php");
}

?>