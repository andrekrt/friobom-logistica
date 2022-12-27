<?php

session_start();
require("../conexao.php");
include("funcao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 8 || $_SESSION['tipoUsuario'] == 99)){
    
    $usuario = $_SESSION['idUsuario'];
    $dataAbastecimento = date("Y-m-d");
    $litro = str_replace(",",".",filter_input(INPUT_POST, 'litro'));
    $placa =  filter_input(INPUT_POST, 'placa');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $tipoAbastecimento = filter_input(INPUT_POST, 'tipo');
    $km = filter_input(INPUT_POST, 'km');
    $estoqueAtual = contaEstoque();

    //echo "$dataAbastecimento<br>$litro<br>$carregamento<br>$placa<br>$tipoAbastecimento<br>$usuario";

    //verifica se já possui esse abastecimento
    $consultaSaidas = $db->prepare("SELECT * FROM combustivel_saida WHERE carregamento = :carregamento && tipo_abastecimento = :tipo");
    $consultaSaidas->bindValue(':carregamento', $carregamento);
    $consultaSaidas->bindValue(':tipo', $tipoAbastecimento);
    $consultaSaidas->execute();
    if($consultaSaidas->rowCount()>0){
        echo "<script>alert('Esse abastecimento já está registrado!');</script>";
        echo "<script>window.location.href='abastecimento.php'</script>";  
    }else{
        if($litro>=$estoqueAtual){
            echo "<script>alert('Estoque Insuficiente');</script>";
            echo "<script>window.location.href='abastecimento.php'</script>";
        }else{

            $total = $db->query("SELECT SUM(total_litros) AS  litros, SUM(valor_total) as valor FROM combustivel_entrada");
            $total = $total->fetch();
            $precoMedio = $total['valor']/$total['litros'];
            $valorTotal = $precoMedio*$litro;

            $inserir = $db->prepare("INSERT INTO combustivel_saida (data_abastecimento, litro_abastecimento, preco_medio, valor_total, carregamento, km, placa_veiculo, tipo_abastecimento, usuario) VALUES (:dataAbastecimento, :litros, :precoMedio, :valorTotal, :carregamento, :km, :placa, :tipo, :usuario)");
            $inserir->bindValue(':dataAbastecimento', $dataAbastecimento);
            $inserir->bindValue(':litros', $litro);
            $inserir->bindValue(':precoMedio', $precoMedio);
            $inserir->bindValue(':valorTotal', $valorTotal);
            $inserir->bindValue(':carregamento', $carregamento);
            $inserir->bindValue(':km', $km);
            $inserir->bindValue(':placa', $placa);
            $inserir->bindValue(':tipo', $tipoAbastecimento);
            $inserir->bindValue(':usuario', $usuario);

            if($inserir->execute()){
                echo "<script>alert('Abastecimento Lançada com Sucesso!');</script>";
                echo "<script>window.location.href='abastecimento.php'</script>";    
                
            }else{
                print_r($inserir->errorInfo());
            }

        }

    }

    

    

}

?>