<?php

include_once '../conexao-oracle.php';
include '../conexao.php';

$nCarregamento = filter_input(INPUT_GET, 'nCarregamento');
if(!empty($nCarregamento)){
    $limite =1;
    $resultado = "SELECT * FROM FRIOBOM.pccarreg WHERE numcar = :nCarregamento";

    $resultado=$dbora->prepare($resultado);
    $resultado->bindParam(':nCarregamento', $nCarregamento, PDO::PARAM_STR);
    $resultado->bindParam(':limite', $limite, PDO::PARAM_INT);
    $resultado->execute();

    //buscar abastecimento
    $sql = $db->prepare("SELECT * FROM combustivel_saida WHERE carregamento = :carregamento");
    $sql->bindValue(':carregamento', $nCarregamento);
    $sql->execute();

    //buscar se existe nota denegado não confirmada
    $sqlNf = $db->prepare("SELECT * FROM denegadas WHERE carga = :carga AND situacao = :situacao");
    $sqlNf->bindValue(':carga', $nCarregamento);
    $sqlNf->bindValue(':situacao', "Aguardando Confirmação");
    $sqlNf->execute();
    $qtdDenegado = $sqlNf->rowCount();

    // verificar se existe caixas pendentes
    $sqlCaixas = $db->prepare("SELECT * FROM caixas WHERE carregamento=:carregamento AND situacao = :situacao");
    $sqlCaixas->bindValue(':carregamento', $nCarregamento);
    $sqlCaixas->bindValue(':situacao', "Saída");
    $sqlCaixas->execute();
    $qtdCaixa = $sqlCaixas->rowCount();

    // verificar se carregamento ja existe
    $qtdCarga = $db->prepare("SELECT * FROM viagem WHERE num_carregemento=:carregamento");
    $qtdCarga->bindValue(':carregamento', $nCarregamento);
    $qtdCarga->execute();
    $qtdCarga = $qtdCarga->rowCount();

    // verificar se tem vale não resgatado
    $sqlVales = $db->prepare("SELECT * FROM vales WHERE carregamento=:carregamento AND situacao = :situacao");
    $sqlVales->bindValue(':carregamento', $nCarregamento);
    $sqlVales->bindValue(':situacao', "Não Resgatado");
    $sqlVales->execute();
    $qtdVales = $sqlVales->rowCount();

    // verificar se tem troca não conferida
    $sqlTroca = $db->prepare("SELECT * FROM trocas WHERE carregamento =:carregamento AND situacao=:situacao");
    $sqlTroca->bindValue(':carregamento', $nCarregamento);
    $sqlTroca->bindValue(':situacao', "Não Conferido");
    $sqlTroca->execute();
    $qtdTrocas = $sqlTroca->rowCount();

    // verificar valor que falta na troca
    $sqlValorTroca = $db->prepare("SELECT SUM(valor_unit*qtd_falta) as vlTotal FROM trocas WHERE carregamento=:carregamento GROUP BY carregamento ");
    $sqlValorTroca->bindValue(':carregamento', $nCarregamento);
    $sqlValorTroca->execute();
    $valorTroca = $sqlValorTroca->fetchColumn();

    // pegar docas
    $sqlDoca = $dbora->prepare("SELECT numbox FROM friobom.PCMOVENDPEND WHERE numcar = :carregamento GROUP BY numbox ORDER BY numbox DESC");
    $sqlDoca->bindValue(':carregamento', $nCarregamento);
    $sqlDoca->execute();
    $doca = $sqlDoca->fetch(PDO::FETCH_ASSOC);

    $valores=array();    

    if($resultado->execute()){
        $carregamento = $resultado->fetch(PDO::FETCH_ASSOC);
        $abastecimento = $sql->fetch();
        $valores['codMotorista'] = $carregamento['CODMOTORISTA'];
        $valores['codVeiculo'] = $carregamento['CODVEICULO']; 
        $valores['codRota'] = $carregamento['CODROTAPRINC'];
        $valores['pesoCarga'] = $carregamento['TOTPESO'];
        $valores['vlTransp'] = $carregamento['VLTOTAL'];
        $valores['kmSaida'] = $carregamento['KMINICIAL'];
        $valores['qtdEntrega'] = $carregamento['NUMENT'];
        $valores['kmAbastecimento'] = $abastecimento['km'];
        $valores['litrosAbastecimento'] = $abastecimento['litro_abastecimento'];
        $valores['valorTotal'] = $abastecimento['valor_total'];
        $valores['local'] = "POSTO FRIOBOM";
        $valores['denegadas']= $qtdDenegado;
        $valores['caixas']=$qtdCaixa;
        $valores['qtdCarregamentos']=$qtdCarga;
        $valores['qtdVales']=$qtdVales;
        $valores['qtdTrocas']= $qtdTrocas;
        $valores['valorTroca']=$valorTroca;
        $valores['doca']= $doca['NUMBOX']; 
    }else{
        $valores['vlTransp']='Não Encontrado';
    }
    echo json_encode($valores);

}

?>