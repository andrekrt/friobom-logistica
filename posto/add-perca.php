<?php

session_start();
require("../conexao.php");
include("funcao.php");

$idModudulo = 13;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
    $usuario = $_SESSION['idUsuario'];
    $dataAbastecimento = date("Y-m-d");
    $litro = str_replace(",",".",filter_input(INPUT_POST, 'litro'));
    $tipoAbastecimento = "Perca";
    $situacao="Pendente";

    $db->beginTransaction();

    try{

        $total = $db->query("SELECT SUM(total_litros) AS  litros, SUM(valor_total) as valor FROM combustivel_entrada WHERE situacao ='Aprovado'");
        $total = $total->fetch();
        $precoMedio = $total['valor']/$total['litros'];
        $valorTotal = $precoMedio*$litro;

        $inserir = $db->prepare("INSERT INTO combustivel_saida (data_abastecimento, litro_abastecimento, preco_medio, valor_total,  tipo_abastecimento, situacao, usuario) VALUES (:dataAbastecimento, :litros, :precoMedio, :valorTotal, :tipo, :situacao, :usuario)");
        $inserir->bindValue(':dataAbastecimento', $dataAbastecimento);
        $inserir->bindValue(':litros', $litro);
        $inserir->bindValue(':precoMedio', $precoMedio);
        $inserir->bindValue(':valorTotal', $valorTotal);
        $inserir->bindValue(':tipo', $tipoAbastecimento);
        $inserir->bindValue(':situacao',$situacao);
        $inserir->bindValue(':usuario', $usuario);
        $inserir->execute();

        // $sqlExtrato = $db->prepare("INSERT INTO combustivel_extrato (data_operacao, tipo_operacao, volume,  usuario) VALUES (:dataOp, :tipoOp, :volume, :usuario)");
        // $sqlExtrato->bindValue(':dataOp', $dataAbastecimento);
        // $sqlExtrato->bindValue(':tipoOp', "Perca");
        // $sqlExtrato->bindValue(':volume', $litro);
        // $sqlExtrato->bindValue(':usuario', $usuario);
        // $sqlExtrato->execute();

        $db->commit();
        echo "<script>alert('Abastecimento Lançada com Sucesso!');</script>";
        echo "<script>window.location.href='abastecimento.php'</script>";  
       
    }catch(Exception $e){
        $db->rollBack();
        echo "Erro: " . $e->getMessage();
    }

    

        

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='abastecimento.php'</script>"; 
}

?>