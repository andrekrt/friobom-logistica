<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] == 2|| $_SESSION['tipoUsuario'] ==99){

    $idCheck = filter_input(INPUT_POST, 'idCheck');
    $placaVeiculo = filter_input(INPUT_POST, 'placaVeiculo');
    $tipoVeiculo = filter_input(INPUT_POST, 'tipoVeiculo');
    $obs = filter_input(INPUT_POST, 'observacoes');
    $qtdeNF=filter_input(INPUT_POST, 'qtd-nf');
    $valorCarga = str_replace(",", ".", filter_input(INPUT_POST, 'valorCarga')) ;
    $dataSaida = filter_input(INPUT_POST, 'dataSaida');
    $horimetro = filter_input(INPUT_POST, 'horimetro');
    $rota = filter_input(INPUT_POST, 'rota');
    $carregamento = filter_input(INPUT_POST, 'numCarga');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $pesoCarga = str_replace(",", ".",filter_input(INPUT_POST, 'pesoCarga') ) ;
    $kmInicial = filter_input(INPUT_POST, 'kmInicial');
    $situacao = filter_input(INPUT_POST, 'situacao');


    //echo "$idCheck<br>$qtdeNF<br>$valorCarga<br>$dataSaida<br>$horimetro<br>$rota<br>$pesoCarga<br>$carregamento<br>$motorista<br>$situacao";
    
    $sql="UPDATE check_list SET qtde_nf = :qtdNf, valor_carga = :valorCarga, data_saida = :dataSaida, horimetro = :horimetro, rota = :rota, peso_carga = :pesoCarga, num_carregemento = :numCarga, motorista = :motorista, situacao=:situacao, km_inicial = :kmInicial, placa_veiculo = :placaVeiculo, tipo_veiculo = :tipoVeiculo, observacoes = :obs WHERE idcheck_list = :id";

    $sql=$db->prepare($sql);
    $sql->bindValue(':id', $idCheck);
    $sql->bindValue(':qtdNf', $qtdeNF);
    $sql->bindValue(':valorCarga', $valorCarga);
    $sql->bindValue(':dataSaida', $dataSaida);
    $sql->bindValue(':horimetro', $horimetro);
    $sql->bindValue(':rota', $rota);
    $sql->bindValue(':pesoCarga', $pesoCarga);
    $sql->bindValue(':numCarga', $carregamento);
    $sql->bindValue(':motorista', $motorista);
    $sql->bindValue(':situacao', $situacao);
    $sql->bindValue(':kmInicial', $kmInicial);
    $sql->bindValue(':placaVeiculo', $placaVeiculo);
    $sql->bindValue(':tipoVeiculo', $tipoVeiculo);
    $sql->bindValue(':obs', $obs);
    
    if($sql->execute()){
        echo "<script> alert('Atualizado com Sucesso')</script>";
        echo "<script> window.location.href='check-list.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }


}

?>