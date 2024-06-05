<?php 

include '../conexao-on.php';

$mes = 02;
$ano = 2024;

$qtdDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
$dias=[];
for($i=1; $i<=$qtdDias;$i++){
    $dias[$i]= $i;
}

$sqlMot = $db->prepare('SELECT nome_motorista, cod_interno_motorista FROM motoristas WHERE ativo=1');
$sqlMot->execute();
$motoristas = $sqlMot->fetchAll(PDO::FETCH_ASSOC);

$sqlViagem= $db->prepare('SELECT data_saida as data_saida, data_chegada as data_chegada FROM viagem WHERE cod_interno_motorista = :motorista AND ( (MONTH(data_saida) = :mes AND YEAR(data_saida) = :ano) OR (MONTH(data_chegada) = :mes AND YEAR(data_chegada) = :ano) )');
$sqlViagem->bindValue(':mes', $mes);
$sqlViagem->bindValue(':ano',$ano);

echo "<table border='1'>";
echo "<tr><th>Motorista</th>";

// Gerar cabeçalho com os dias do mês
for ($i = 1; $i <= $qtdDias; $i++) {
  echo "<th colspan='2'>" . $i . "/$mes</th>";
}

echo "</tr>";

// gerar linha com turnos
echo "<tr>";
echo "<th></th>";
for ($i = 1; $i <= $qtdDias; $i++) {
  echo "<th>Manhã</th>";
  echo "<th>Tarde</th>";
}
echo "</tr>";

foreach ($motoristas as $motorista) {
    echo "<tr><td>" . $motorista['nome_motorista'] . "</td>";
    $sqlViagem->bindValue(':motorista', $motorista['cod_interno_motorista']);
    $sqlViagem->execute();
    $viagens = $sqlViagem->fetchAll(PDO::FETCH_ASSOC);

     

    // Verificar se há viagens para o motorista em cada dia
    for ($i = 1; $i <= $qtdDias; $i++) {
      $viagem_manha = false;
      $viagem_tarde = false;

      $manhaInicio = $ano. '-'.$mes.'-'.$i;

      $manhaInicioFormat = date('Y-m-d', strtotime($manhaInicio));

      // var_dump($motorista['nome_motorista'] . " || " . $manhaInicioFormat);
      // var_dump($viagens);
      // echo "<hr><br>";

      foreach ($viagens as $viagem) {
        $dataSaida = date('Y-m-d', strtotime($viagem['data_saida']));
        $dataChegada = date('Y-m-d', strtotime($viagem['data_chegada']));

        // Comparar a data da viagem com o dia
        if ( $manhaInicioFormat > $dataSaida && $manhaInicioFormat < $dataChegada) {
          $viagem_manha = true;
          $viagem_tarde = true;
          break;  
          
        }elseif($manhaInicioFormat==$dataSaida || $manhaInicioFormat==$dataChegada){
          
          if($dataSaida==$manhaInicioFormat){
            $hora = date('H:i', strtotime($viagem['data_saida']));
            if($hora<='12:00'){
              $viagem_manha = true;
              $viagem_tarde = true;
            }else{
              $viagem_tarde= true;
            }
          }
          
          if($dataChegada==$manhaInicioFormat){
            $hora = date('H:i', strtotime($viagem['data_chegada']));
            if($hora<='12:00'){
              $viagem_manha = true;
            }else{
              $viagem_manha = true;
              $viagem_tarde = true;
                
            }
          }
          break;
        }
          
        // // Comparar a data da viagem com o dia
        // if ( $manhaInicioFormat > $dataSaida && $manhaInicioFormat < $dataChegada) {
        //   $viagem_manha = true;
        //   $viagem_tarde = true;
        //   break;            
          
        // }elseif($manhaInicioFormat = $dataSaida || $manhaInicioFormat = $dataChegada){

        // }

      }
    
      if ($viagem_manha) {
        echo "<td style='background-color: #00FF00'>Em viagem</td>";
      } else {
        echo "<td style='background-color: #FF0000'>Em casa</td>";
      }

      if ($viagem_tarde) {
        echo "<td style='background-color: #00FF00'>Em viagem</td>";
      } else {
        echo "<td style='background-color: #FF0000'>Em casa</td>";
      }

    }
  
    echo "</tr>";
}