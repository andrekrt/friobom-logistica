<?php

session_start();
require("../conexao.php");

$id = filter_input(INPUT_GET, 'id');

if(isset($id) && empty($id) == false ){ 
    
    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE auxiliares_rota SET ativo=0 WHERE idauxiliares=:id ");
        $sql->bindValue(':id',$id);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Auxiliar Desativado com Sucesso';
        $_SESSION['icon']='success';

    } catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Desativar Auxiliar';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: auxiliares.php");
exit();
?>