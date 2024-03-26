<?php

session_start();
require("../conexao.php");

$idModudulo = 17;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = filter_input(INPUT_GET, 'id');

    $db->beginTransaction();

    try{
        $delete = $db->prepare("DELETE FROM fornecedores WHERE id = :id ");
        $delete->bindValue(':id', $id);
        $delete->execute();

        $db->commit();

        $_SESSION['msg'] = 'Fornecedor Excluído com Sucesso!';
        $_SESSION['icon']='success';
    
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Excluir Fornecedor';
        $_SESSION['icon']='error';
    }

    header("Location: fornecedores.php");
    exit();

}else{
    echo "Erro";
}

?>