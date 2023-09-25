<?php

session_start();
require("../conexao.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    //$token = sprintf('%07X', mt_rand(0, 0xFFFFFFF));
    $consultaToken = $db->query("SELECT MAX(token) as token FROM inventario_almoxarifado");
    $token = $consultaToken->fetch();
    if(empty($token['token'])){
        $newToken = 0+1;
    }else{
        $newToken = $token['token']+1;
    }

    $dataAtual = date("Y/m/d");
    $peca = $_POST['peca'];
    $qtd = str_replace(",",".",$_POST['qtd']) ;;
    $usuario = $_SESSION['idUsuario'];
    
    for($i=0; $i<count($peca); $i++){

        $sql = $db->prepare("INSERT INTO inventario_almoxarifado (token, data_inv, peca, qtd, usuario) VALUES (:token, :dataAtual, :peca, :qtd, :usuario)");
        $sql->bindValue(':token', $newToken);
        $sql->bindValue(':dataAtual', $dataAtual);
        $sql->bindValue(':peca', $peca[$i]);
        $sql->bindValue(':qtd', $qtd[$i]);
        $sql->bindValue(':usuario', $usuario);
        
        if($sql->execute()){
            $atualiza = $db->prepare("UPDATE peca_reparo SET qtd_inv = :qtd WHERE id_peca_reparo = :idpeca ");
            $atualiza->bindValue(':idpeca', $peca[$i]);
            $atualiza->bindValue(':qtd', $qtd[$i]);
            if($atualiza->execute()){
                echo "<script> alert('Inventário Realizado')</script>";
                echo "<script> window.location.href='inventario.php' </script>";
            }else{
                print_r($sql->errorInfo());
            }
        }else{
            print_r($sql->errorInfo());
        }

    }

}else{

}

?>