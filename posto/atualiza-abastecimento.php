<?php

session_start();
require("../conexao.php");
include("funcao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99)){
    
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

    // echo "$litro<br>$carregamento<br>$placa<br>$tipoAbastecimento<br>$km<br>$id<br>$usuario";

    if($litro>=$estoqueAtual){
        echo "<script>alert('Estoque Insuficiente');</script>";
        echo "<script>window.location.href='abastecimento.php'</script>";
    }else{

        $total = $db->query("SELECT SUM(total_litros) AS  litros, SUM(valor_total) as valor FROM combustivel_entrada");
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

        if($atualiza->execute()){
            echo "<script>alert('Abastecimento Atualizado com Sucesso!');</script>";
            echo "<script>window.location.href='abastecimento.php'</script>";    
            
        }else{
            print_r($inserir->errorInfo());
        }

    } 

}

?>