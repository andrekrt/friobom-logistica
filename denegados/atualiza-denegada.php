<?php

session_start();
require("../conexao.php");

$idModudulo = 8;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idUsuario = $_SESSION['idUsuario'];
    $id = $_POST['id'];
    $carga = $_POST['carga'];
    $nf = $_POST['nf'];
    $situacao = $_POST['status'];

    for($i=0;$i<count($id);$i++){
        $atualiza = $db->prepare("UPDATE denegadas SET carga = :carga, nf = :nf, situacao = :situacao WHERE id_denegadas = :id");
        $atualiza->bindValue(':carga', $carga[$i]);
        $atualiza->bindValue(':nf', $nf[$i]);
        $atualiza->bindValue(':situacao', $situacao[$i]);
        $atualiza->bindValue(':id', $id[$i]);

        if($atualiza->execute()){
            echo "<script> alert('Atualizado com Sucesso!')</script>";
            echo "<script> window.location.href='denegadas.php' </script>";
        }else{
            print_r($db->errorInfo());
        }
    }
    

}else{
    echo "Erro";
}

?>