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

    $idNf = filter_input(INPUT_POST,'idNf');
    $obs = filter_input(INPUT_POST, 'obs');

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE nfs_xml SET obs =:obs WHERE idNf=:id");
        $sql->bindValue(':obs', $obs);
        $sql->bindValue(':id', $idNf);
        $sql->execute();       

        $db->commit();
        
        $_SESSION['msg'] = 'Obs Lançada com Sucesso';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
       
        $_SESSION['msg'] = 'Erro ao Lançar Obs';
        $_SESSION['icon']='error';
    }    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: listar.php");
exit();
?>