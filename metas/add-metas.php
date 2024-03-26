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

    $tipoMeta = filter_input(INPUT_POST, 'tipoMeta');
    $dataMeta =  $_POST['data'];
    $valorMeta = $_POST['meta'];
    $usuario = $_SESSION['idUsuario'];

    $db->beginTransaction();

    try{
        //consulta token
        $sqlToken = $db->query("SELECT MAX(TOKEN) as token FROM metas");
        $token = $sqlToken->fetch();
        $token=$token['token']+1;

        for($i=0; $i<count($dataMeta);$i++){
            $data = date('Y-m-d', strtotime(str_replace("/","-", $dataMeta[$i])));
            
            $sql = $db->prepare("INSERT INTO metas (token, tipo_meta, data_meta, valor_meta, usuario) VALUES (:token,:tipo, :dataMeta, :meta, :usuario)");
            $sql->bindValue(':token', $token);
            $sql->bindValue(':tipo', $tipoMeta);
            $sql->bindValue(':dataMeta', $data);
            $sql->bindValue(':meta', $valorMeta[$i]);
            $sql->bindValue(':usuario', $usuario);
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