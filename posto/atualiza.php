<?php
include '../conexao-on.php';

$consulta = $db->prepare("SELECT SUM(total_litros) as totalLitros, SUM(valor_total) as valorTotal FROM combustivel_entrada");
if($consulta->execute()){
    $consulta = $consulta->fetch();
    $litros = $consulta['totalLitros'];
    $valor = $consulta['valorTotal'];
    $precoMedio = $valor/$litros;

    $consultaSaida = $db->query("SELECT * FROM combustivel_saida");
    $saidas=$consultaSaida->fetchAll();
    foreach($saidas as $saida){
        $litrosAbastecido = $saida['litro_abastecimento'];
        $valorTotal = $litrosAbastecido*$precoMedio;

        $atualiza = $db->prepare("UPDATE combustivel_saida SET preco_medio = :preco, valor_total = :valor WHERE idcombustivel_saida = :id");
        $atualiza->bindValue(':preco', $precoMedio);
        $atualiza->bindValue(':valor', $valorTotal);
        $atualiza->bindValue(':id', $saida['idcombustivel_saida']);
        if($atualiza->execute()){
            echo "certo<br>";
        }else{
            print_r($atualiza->errorInfo());
        }
        
    }

   // echo "Litros Total = $litros<br> Valor Total = $valor <br> Preço= $precoMedio";
}else{
    print_r($consulta->errorInfo());
}




?>