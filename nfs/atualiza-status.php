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

    $id = filter_input(INPUT_GET, 'id');
    $situacao = filter_input(INPUT_GET, 'status');

    // echo "$id<br>$situacao";

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE nfs_xml SET situacao =:situacao WHERE idnf=:id");
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':id', $id);
        $sql->execute();           

        $db->commit();
        
        $_SESSION['msg'] = 'NF Atualizada com Sucesso';
        $_SESSION['icon']='success';
        // echo json_encode(true);
    }catch(Exception $e){
        $db->rollBack();
       
        $_SESSION['msg'] = 'Erro ao Atualizar NF';
        $_SESSION['icon']='error';
        // echo json_encode(false);
    }    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: listar.php");
exit();


?>