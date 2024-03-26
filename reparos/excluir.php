<?php

session_start();
require("../conexao.php");

$token = filter_input(INPUT_GET, 'token');

if(isset($token) && empty($token) == false ){
    $id=$_SESSION['idUsuario'];  

    $db->beginTransaction();

    try{
        $sql = $db->prepare("DELETE FROM solicitacoes_new WHERE token = :token");
        $sql->bindValue(':token',$token);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Solicitação Excluída com Sucesso!';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Solicitação';
        $_SESSION['icon']='error';
    }
    
}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: solicitacoes.php");
exit();
?>