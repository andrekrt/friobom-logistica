<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 8 || $_SESSION['tipoUsuario'] == 99)){
    
    $idinventario = filter_input(INPUT_POST, 'id');
    $litros = str_replace(",",".",filter_input(INPUT_POST, 'litros'));
    
    //echo "$idinventario<br>$litros";

    $atualizar = $db->prepare("UPDATE combustivel_inventario SET qtd_encontrada = :volume WHERE idinventario = :id");
    $atualizar->bindValue(':volume', $litros);
    $atualizar->bindValue(':id', $idinventario);

    if($atualizar->execute()){
        echo "<script>alert('Inventario Atualizada com Sucesso!');</script>";
        echo "<script>window.location.href='inventario.php'</script>";    
        
    }else{
        print_r($atualizar->errorInfo());
    }

}

?>