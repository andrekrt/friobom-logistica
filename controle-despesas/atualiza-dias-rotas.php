<?php 

require "../conexao-on.php";

$sql = $db->query("SELECT dias_em_rota, data_saida, data_chegada, iddespesas FROM viagem ORDER BY iddespesas DESC");
$dados = $sql->fetchAll();

foreach($dados as $dado){
    $dataSaida= $dado['data_saida'];
    $dataChegada = $dado['data_chegada'];

    //calculo de diferença de datas
    $dataFinial = new DateTime($dataChegada);
    $dataInicial = new DateTime($dataSaida);
    $diferencaDias = $dataFinial->diff($dataInicial);
    $diasEmRota= number_format($diferencaDias->days+($diferencaDias->h/24) + ($diferencaDias->i/1440),2) ;

    $update = $db->prepare("UPDATE viagem SET dias_em_rota =:diasRota WHERE iddespesas =:id ");
    $update->bindValue(':diasRota', $diasEmRota);
    $update->bindValue(':id', $dado['iddespesas']);
    if($update->execute()){
        echo "certo<br>";
    }else{
        print_r($update->errorInfo());
    }

    // echo " ID: " . $dado['iddespesas']. " || ";
    // echo " Dias em Rota ". $diasEmRota." || ";
    // echo " Data Saída ". $dado['data_saida']." || ";
    // echo " Data Chegada ". $dado['data_chegada']."<br><br>";
}