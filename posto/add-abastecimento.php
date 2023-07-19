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
    $placa =  filter_input(INPUT_POST, 'placa');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $tipoAbastecimento = filter_input(INPUT_POST, 'tipo');
    $km = filter_input(INPUT_POST, 'km');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $rota = filter_input(INPUT_POST, 'rota');
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

            $total = $db->query("SELECT SUM(total_litros) AS  litros, SUM(valor_total) as valor FROM combustivel_entrada WHERE situacao ='Aprovado'");
            $total = $total->fetch();
            $precoMedio = $total['valor']/$total['litros'];
            $valorTotal = $precoMedio*$litro;

            $inserir = $db->prepare("INSERT INTO combustivel_saida (data_abastecimento, litro_abastecimento, preco_medio, valor_total, carregamento, motorista, rota, km, placa_veiculo, tipo_abastecimento, usuario) VALUES (:dataAbastecimento, :litros, :precoMedio, :valorTotal, :carregamento, :motorista, :rota, :km, :placa, :tipo, :usuario)");
            $inserir->bindValue(':dataAbastecimento', $dataAbastecimento);
            $inserir->bindValue(':litros', $litro);
            $inserir->bindValue(':precoMedio', $precoMedio);
            $inserir->bindValue(':valorTotal', $valorTotal);
            $inserir->bindValue(':carregamento', $carregamento);
            $inserir->bindValue(':motorista', $motorista);
            $inserir->bindValue(':rota', $rota);
            $inserir->bindValue(':km', $km);
            $inserir->bindValue(':placa', $placa);
            $inserir->bindValue(':tipo', $tipoAbastecimento);
            $inserir->bindValue(':usuario', $usuario);

            if($inserir->execute()){
                $sqlExtrato = $db->prepare("INSERT INTO combustivel_extrato (data_operacao, tipo_operacao, volume, carregamento, placa, usuario) VALUES (:dataOp, :tipoOp, :volume,:carga, :placa, :usuario)");
                $sqlExtrato->bindValue(':dataOp', $dataAbastecimento);
                $sqlExtrato->bindValue(':tipoOp', "Abastecimento");
                $sqlExtrato->bindValue(':volume', $litro);
                $sqlExtrato->bindValue(':carga', $carregamento);
                $sqlExtrato->bindValue(':placa', $placa);
                $sqlExtrato->bindValue(':usuario', $usuario);
                if($sqlExtrato->execute()){
                    echo "<script>alert('Abastecimento Lançada com Sucesso!');</script>";
                    echo "<script>window.location.href='abastecimento.php'</script>";  
                }else{
                    print_r($sqlExtrato->errorInfo());
                }
                  
                
            }else{
                print_r($inserir->errorInfo());
            }

        }

    }

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='abastecimento.php'</script>"; 
}

?>