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
    $id = filter_input(INPUT_POST,'id');
    $carga = filter_input(INPUT_POST,'carga');
    $qtd = filter_input(INPUT_POST,'qtd');
  
    // echo "$idUsuario<br>$id<br>$carga<br>$qtd";

    $atualiza = $db->prepare("UPDATE caixas SET carregamento = :carregamento, qtd_caixas = :qtd WHERE idcaixas = :id");
    $atualiza->bindValue(':carregamento', $carga);
    $atualiza->bindValue(':qtd', $qtd);
    $atualiza->bindValue(':id', $id);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='caixas.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>