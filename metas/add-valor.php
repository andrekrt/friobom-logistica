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

    for($i=0; $i<count($valorMeta);$i++){
        
        // echo "ID: $id[$i] - Valor Meta: $valorMeta[$i]<br>"; 
        
        $sql = $db->prepare("UPDATE metas SET valor_alcancado=:valor WHERE idmetas = :id");
        $sql->bindValue(':id', $id[$i]);
        $sql->bindValue(':valor', $valorMeta[$i]);

        if($sql->execute()){
            echo "<script> alert('Meta Registrada!!')</script>";
            echo "<script> window.location.href='metas.php' </script>";
            
        }else{
            print_r($sql->errorInfo());
        }

    }

}else{
    echo "<script> alert('Acesso não permitido!!!')</script>";
    echo "<script> window.location.href='form-suco.php' </script>";
}

?>