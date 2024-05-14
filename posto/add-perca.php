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
    $filial = $_SESSION['filial'];
    $usuario = $_SESSION['idUsuario'];
    $dataAbastecimento = date("Y-m-d");
    $litro = str_replace(",",".",filter_input(INPUT_POST, 'litro'));
    $tipoAbastecimento = "Perca";
    $situacao="Pendente";

    $db->beginTransaction();

    try{

        $total = $db->query("SELECT SUM(total_litros) AS  litros, SUM(valor_total) as valor FROM combustivel_entrada WHERE situacao ='Aprovado' AND filial = $filial");
        $total = $total->fetch();
        $precoMedio = $total['valor']/$total['litros'];
        $valorTotal = $precoMedio*$litro;

        $inserir = $db->prepare("INSERT INTO combustivel_saida (data_abastecimento, litro_abastecimento, preco_medio, valor_total,  tipo_abastecimento, situacao, usuario, filial) VALUES (:dataAbastecimento, :litros, :precoMedio, :valorTotal, :tipo, :situacao, :usuario, :filial)");
        $inserir->bindValue(':dataAbastecimento', $dataAbastecimento);
        $inserir->bindValue(':litros', $litro);
        $inserir->bindValue(':precoMedio', $precoMedio);
        $inserir->bindValue(':valorTotal', $valorTotal);
        $inserir->bindValue(':tipo', $tipoAbastecimento);
        $inserir->bindValue(':situacao',$situacao);
        $inserir->bindValue(':usuario', $usuario);
        $inserir->bindValue(':filial', $filial);
        $inserir->execute();

        $db->commit();
        $_SESSION['msg'] = 'Perca Lançada com Sucesso!';
        $_SESSION['icon']='success';
       
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Perca';
        $_SESSION['icon']='error';
    }        

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning'; 
}
header("Location: abastecimento.php");
exit();
?>