<?php

session_start();
require("../conexao.php");

$idModudulo = 15;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = $_POST['id'];
    $valorMeta = $_POST['valor'];

    $db->beginTransaction();

    try{
        for($i=0; $i<count($valorMeta);$i++){
            
            $sql = $db->prepare("UPDATE metas SET valor_alcancado=:valor WHERE idmetas = :id");
            $sql->bindValue(':id', $id[$i]);
            $sql->bindValue(':valor', $valorMeta[$i]);
            $sql->execute();    
        }

        $db->commit();

        $_SESSION['msg'] = 'Meta Registrada com Sucesso!';
        $_SESSION['icon']='success';
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Registrar Meta';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: metas.php");
exit();
?>