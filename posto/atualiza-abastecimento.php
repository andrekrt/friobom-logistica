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
    $litro = str_replace(",",".",filter_input(INPUT_POST, 'litro'));
    $placa =  filter_input(INPUT_POST, 'placa');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $tipoAbastecimento = filter_input(INPUT_POST, 'tipo');
    $km = filter_input(INPUT_POST, 'km');
    $id=filter_input(INPUT_POST,'id');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $rota = filter_input(INPUT_POST, 'rota');
    $estoqueAtual = contaEstoque();

   $db->beginTransaction();

   try{
    if($litro>=$estoqueAtual){
        $_SESSION['msg'] = 'Estoque Insuficiente!';
        $_SESSION['icon']='warning';
        header("Location: abastecimento.php");
        exit();
    }

    $total = $db->query("SELECT SUM(total_litros) AS  litros, SUM(valor_total) as valor FROM combustivel_entrada WHERE situacao = 'Aprovado'");
    $total = $total->fetch();
    $precoMedio = $total['valor']/$total['litros'];
    $valorTotal = $precoMedio*$litro;

    $atualiza = $db->prepare("UPDATE combustivel_saida SET litro_abastecimento=:litros, preco_medio=:precoMedio, valor_total=:valorTotal, carregamento=:carregamento, km=:km, placa_veiculo=:placa, tipo_abastecimento=:tipo, motorista=:motorista, rota=:rota,  usuario=:usuario WHERE idcombustivel_saida=:id");
    $atualiza->bindValue(':litros', $litro);
    $atualiza->bindValue(':precoMedio', $precoMedio);
    $atualiza->bindValue(':valorTotal', $valorTotal);
    $atualiza->bindValue(':carregamento', $carregamento);
    $atualiza->bindValue(':km', $km);
    $atualiza->bindValue(':placa', $placa);
    $atualiza->bindValue(':tipo', $tipoAbastecimento);
    $atualiza->bindValue(':motorista', $motorista);
    $atualiza->bindValue(':rota', $rota);
    $atualiza->bindValue(':usuario', $usuario);
    $atualiza->bindValue(':id', $id);
    $atualiza->execute();

    $db->commit();

    $_SESSION['msg'] = 'Abastecimento Atualizado com Sucesso!';
    $_SESSION['icon']='success';
    
   }catch(Exception $e){
    $db->rollBack();
    $_SESSION['msg'] = 'Erro ao Atualizar Abastecimento';
    $_SESSION['icon']='error';
   }

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: abastecimento.php");
exit();
?>