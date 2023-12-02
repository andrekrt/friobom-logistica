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
    }else{
        $valores['vlTransp']='Não Encontrado';
    }
    echo json_encode($valores);

}

?>