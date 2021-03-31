<?php

session_start();
require("../conexao.php");

if( isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario']==5 || $_SESSION['tipoUsuario'] ==99){

    $idUsuario = $_SESSION['idUsuario'];

    $numcarreg = filter_input(INPUT_POST, 'carregamento');
    $doca = filter_input(INPUT_POST, 'doca');
    $rota = filter_input(INPUT_POST, 'rota');
    $peso = str_replace(",", ".",filter_input(INPUT_POST, 'peso'));

    $consulta = $db->prepare("SELECT * FROM carregamentos WHERE num_carreg = :numCarreg");
    $consulta->bindValue(":numCarreg", $numcarreg);
    $consulta->execute();

    if($consulta->rowCount()>2){
        echo "<script>alert('Esse carregamento já está Lançado!');</script>";
        echo "<script>window.location.href='form-carregamento.php'</script>";
    }else{
        $sql= $db->prepare("INSERT INTO carregamentos (num_carreg, doca, rota, peso, situacao) VALUES (:numCarreg, :doca, :rota, :peso, :situacao) ");
        $sql->bindValue(":numCarreg", $numcarreg);
        $sql->bindValue(":doca", $doca);
        $sql->bindValue(":rota", $rota);
        $sql->bindValue(":peso", $peso);
        $sql->bindValue(":situacao", "Iniciado");
        if($sql->execute()){

            echo "<script>alert('Carregamento Lançado!');</script>";
            echo "<script>window.location.href='form-carregamento.php'</script>";

        }else{
            print_r($sql->errorInfo());
        }
    }

}else{
    echo "<script>alert('Acesso não Permitido');</script>";
    echo "<script>window.location.href='form-carregamento.php'</script>";
}

?>