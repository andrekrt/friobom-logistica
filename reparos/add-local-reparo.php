<?php 

session_start();
require("../conexao.php");

$idModudulo = 9;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $local = filter_input(INPUT_POST, 'local');
    $responsavel = filter_input(INPUT_POST, 'responsavel');
    $telefone = filter_input(INPUT_POST, 'telefone');

    $sql = $db->prepare("INSERT INTO local_reparo (nome_local, responsavel, telefone) VALUES (:nomeLocal, :responsavel, :telefone)");
    $sql->bindValue(':nomeLocal', $local);
    $sql->bindValue(':responsavel', $responsavel);
    $sql->bindValue(':telefone', $telefone);

    if($sql->execute()){
        echo "<script> alert('Local de Reparo Cadastado!')</script>";
        echo "<script> window.location.href='local-reparo.php' </script>";
    }else{
        print_r($inserir->errorInfo());
    }


}else{

    echo "<script> alert('Acesso não permitido')</script>";
    echo "<script> window.location.href='local-reparo.php' </script>";

}

?>