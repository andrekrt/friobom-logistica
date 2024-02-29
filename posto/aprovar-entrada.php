<?php

session_start();
require("../conexao.php");

$idModudulo = 13;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
    $idEntrada = filter_input(INPUT_GET, 'idEntrada');
    $situacao = "Aprovado";
    $litros = filter_input(INPUT_GET, 'lt');

    // echo "ID: $idEntrada Situação: $situacao Litros: $litros";

    $inserir = $db->prepare("UPDATE combustivel_entrada SET situacao = :situacao WHERE idcombustivel_entrada = :id");
    $inserir->bindValue(':situacao', $situacao);
    $inserir->bindValue(':id', $idEntrada);
    

    if($inserir->execute()){
        $sqlExtrato = $db->prepare("INSERT INTO combustivel_extrato (data_operacao, tipo_operacao, volume, usuario) VALUES (:dataOp, :tipoOp, :volume, :usuario)");
        $sqlExtrato->bindValue(':dataOp', date('Y-m-d'));
        $sqlExtrato->bindValue(':tipoOp', "Ajuste de Entrada");
        $sqlExtrato->bindValue(':volume', $litros);
        $sqlExtrato->bindValue(':usuario', $_SESSION['idUsuario']);
        if($sqlExtrato->execute()){
            echo "<script>alert('Entrada Aprovada com Sucesso!');</script>";
            echo "<script>window.location.href='entradas.php'</script>";
        }else{
            print_r($sqlExtrato->errorInfo());
        }
            
        
    }else{
        print_r($inserir->errorInfo());
    }

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='entradas.php'</script>"; 
}

?>