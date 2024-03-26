<?php

session_start();
require("../conexao.php");

$idModudulo = 6;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idOcorrencias = filter_input(INPUT_GET, 'idOcorrencia');

    $db->beginTransaction();
    
    try{
        $delete = $db->prepare("DELETE FROM ocorrencias WHERE idocorrencia = :idOcorrencia ");
        $delete->bindValue(':idOcorrencia', $idOcorrencias);
        $delete->execute();

        $db->commit();

        $_SESSION['msg'] = 'Ocorrência Excluída com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Excluir Ocorrência';
        $_SESSION['icon']='error';
    }   

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: ocorrencias.php");
exit();
?>