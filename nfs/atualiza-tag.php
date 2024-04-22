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

    $coluna = filter_input(INPUT_POST, 'coluna');
    $valor = filter_input(INPUT_POST, 'valor');
    $descricao = filter_input(INPUT_POST, 'descricao');
    $legenda = filter_input(INPUT_POST, 'legenda');
    $cor = filter_input(INPUT_POST, 'cor');
    $idtag = filter_input(INPUT_POST,'id');

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE tags_xml SET nome_coluna =:coluna, descricao=:descricao, valor=:valor, legenda=:legenda, cor=:cor WHERE idtags=:id");
        $sql->bindValue(':coluna', $coluna);
        $sql->bindValue(':descricao', $descricao);
        $sql->bindValue(':valor', $valor);
        $sql->bindValue(':legenda', $legenda);
        $sql->bindValue(':cor', $cor);
        $sql->bindValue(':id', $idtag);
        $sql->execute();       

        $db->commit();
        
        $_SESSION['msg'] = 'Tag Atualizada com Sucesso';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
       
        $_SESSION['msg'] = 'Erro ao Atualizar Tag';
        $_SESSION['icon']='error';
    }    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: tags.php");
exit();
?>