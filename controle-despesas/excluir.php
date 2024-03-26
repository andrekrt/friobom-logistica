<?php

session_start();
require("../conexao.php");

$idModudulo = 7;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $idDespesa = filter_input(INPUT_GET, 'id');
    
    $db->beginTransaction();

    try{
        $sql = $db->prepare("DELETE FROM viagem WHERE iddespesas = :id ");
        $sql->bindValue(':id',$idDespesa);
        $sql->execute();
        
        $db->commit();

        $_SESSION['msg'] = 'Despesa Excluída com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Excluir Despesa';
        $_SESSION['icon']='error';
    }

   
}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: despesas.php");
exit();
?>