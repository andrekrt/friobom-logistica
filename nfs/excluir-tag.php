<?php

use Mpdf\Tag\Input;

session_start();
require("../conexao.php");

$idModudulo = 21;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idtag = filter_input(INPUT_GET,'id');

    $db->beginTransaction();

    try{
        $sql = $db->prepare("DELETE FROM tags_xml WHERE idtags =:id");
        $sql->bindValue(':id', $idtag);
        $sql->execute();       

        $db->commit();
        
        $_SESSION['msg'] = 'Tag Excluída com Sucesso';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
       
        $_SESSION['msg'] = 'Erro ao Excluir Tag';
        $_SESSION['icon']='error';
    }    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: tags.php");
exit();
?>