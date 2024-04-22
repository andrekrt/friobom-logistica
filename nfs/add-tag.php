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
    $valores = $_POST['valor'];
    $descricao = filter_input(INPUT_POST, 'descricao');
    $legenda = filter_input(INPUT_POST, 'legenda');
    $cor = filter_input(INPUT_POST, 'cor');

    $db->beginTransaction();

    try{
        foreach($valores as $valor){        
            //verificar se já existe a tag
            $sqlConsulta = $db->prepare("SELECT * FROM tags_xml WHERE nome_coluna=:coluna AND valor=:valor");
            $sqlConsulta->bindValue(':coluna', $coluna);
            $sqlConsulta->bindValue(':valor', $valor);
            $sqlConsulta->execute();
            if($sqlConsulta->rowCount()>0){
                $_SESSION['msg'] = 'Essa Tag já está cadastrada!';
                $_SESSION['icon']='warning';
                header("Location: tags.php");
                exit();
            }
            $sql = $db->prepare("INSERT INTO tags_xml (nome_coluna, descricao, valor, legenda, cor) VALUES (:coluna, :descricao, :valor, :legenda, :cor)");
            $sql->bindValue(':coluna', $coluna);
            $sql->bindValue(':descricao', $descricao);
            $sql->bindValue(':valor', $valor);
            $sql->bindValue(':legenda', $legenda);
            $sql->bindValue(':cor', $cor);
            $sql->execute();

        }
        
        $db->commit();

        $_SESSION['msg'] = 'Tag Cadastrada com Sucesso';
        $_SESSION['icon']='success';
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Tag';
        $_SESSION['icon']='error';
    }    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: tags.php");
exit();
?>