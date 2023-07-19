<?php 

session_start();
require("../conexao.php");

$idModudulo = 4;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $codMotorista = filter_input(INPUT_GET, 'codMotorista');
    $delete = $db->query("DELETE FROM motoristas WHERE cod_interno_motorista = '$codMotorista' ");
    if($delete){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='motoristas.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro, contatar o adminstrador!";
}

?>