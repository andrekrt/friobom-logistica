<?php

session_start();
require("../conexao.php");

$idModudulo = 19;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = filter_input(INPUT_GET, 'idvale');

   
    $db->beginTransaction();

    try{
        $delete = $db->prepare("DELETE FROM vales WHERE idvale = :id ");
        $delete->bindValue(':id', $id);
        $delete->execute();

        $db->commit();

        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='vales.php' </script>";
   

    }catch(Exception $e){
        $db->rollBack();
        echo "Erro ". $e->getMessage();
    }

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='inventario.php'</script>"; 
}

?>