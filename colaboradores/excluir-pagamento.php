<?php

session_start();
require("../conexao.php");

$id = filter_input(INPUT_GET, 'id');

if(isset($id) && empty($id) == false ){ 
    
    $sql = $db->prepare("DELETE FROM folha_pagamento WHERE idpagamento = :id");
    $sql->bindValue(':id',$id);
    
    if($sql->execute()){
        echo "<script>alert('Excluído com Sucesso!');</script>";
        echo "<script>window.location.href='pagamentos.php'</script>";
    }else{
        print_r($sql->errorInfo());
    }
    
}else{
    header("Location:solicitacoes.php");
}

?>