<?php

session_start();
require("../conexao.php");

$id = filter_input(INPUT_GET, 'id');

if(isset($id) && empty($id) == false ){ 
    
    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE colaboradores SET ativo = 0 WHERE idcolaboradores=:id");
        $sql->bindValue(':id',$id);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Colaborador Desativado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Desativar Colaborador';
        $_SESSION['icon']='error';
    }
    
}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}

header("Location: colaboradores.php");
exit();
?>