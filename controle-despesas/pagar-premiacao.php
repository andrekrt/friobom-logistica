<?php

session_start();
require("../conexao-on.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && ($_SESSION['tipoUsuario'] == 99 ||$_SESSION['tipoUsuario'] == 11)){

    $idUsuario = $_SESSION['idUsuario'];

    $idDespesa = filter_input(INPUT_GET,'id');

    $sql = $db->prepare("UPDATE viagem SET pago = :pago WHERE iddespesas = :id ");
    $sql->bindValue(':pago', 'SIM');
    $sql->bindValue(':id', $idDespesa);

    if($sql->execute()){
        echo "<script>alert('Pagamento Realizado!');</script>";
        echo "<script>window.location.href='premiacoes.php'</script>";
    }else{
        print_r($db->errorInfo());
    }
    

}else{
    echo "<script>alert('Acesso não permitido!');</script>";
    echo "<script>window.location.href='premiacoes.php'</script>";
}

?>