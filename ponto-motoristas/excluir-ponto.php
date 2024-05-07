<?php

session_start();
require("../conexao.php");

$idModudulo = 22;
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
        $delete = $db->prepare("DELETE FROM motoristas_ponto WHERE idponto = :id ");
        $delete->bindValue(':id', $id);
        $delete->execute();

        $db->commit();

        $_SESSION['msg'] = 'Ponto Excluído com Sucesso!';
        $_SESSION['icon']='success';
    
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Excluir Ponto';
        $_SESSION['icon']='error';
    }

    header("Location: pontos.php");
    exit();

}else{
    echo "Erro";
}

?>