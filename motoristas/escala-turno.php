<?php

include '../conexao-on.php';

$mes = 02;
$ano = 2024;

$qtdDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
$dias = [];
for ($i = 1; $i <= $qtdDias; $i++) {
  $dias[$i] = $i;
}

$sqlMot = $db->prepare('SELECT nome_motorista, cod_interno_motorista FROM motoristas WHERE ativo=1 AND cidade_base= "Bacabal" ');
$sqlMot->execute();
$motoristas = $sqlMot->fetchAll(PDO::FETCH_ASSOC);

$sqlViagem = $db->prepare('SELECT DATE(data_saida) AS data_saida, DATE(data_chegada) AS data_chegada FROM viagem WHERE cod_interno_motorista = :motorista AND ( (MONTH(data_saida) = :mes AND YEAR(data_saida) = :ano) OR (MONTH(data_chegada) = :mes AND YEAR(data_chegada) = :ano) )');
$sqlViagem->bindValue(':mes', $mes);
$sqlViagem->bindValue(':ano', $ano);

echo "<table border='1'>";

// Gerar cabeçalho com os dias do mês
echo "<tr>";
echo "<th>Motorista</th>";
for ($i = 1; $i <= $qtdDias; $i++) {
  echo "<th colspan='2'>" . $i . "/$mes</th>";
}
echo "</tr>";

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

  // Verificar se há viagens para o motorista em cada dia e turno
  for ($i = 1; $i <= $qtdDias; $i++) {
    $viagem_manhã_encontrada = false;
    $viagem_tarde_encontrada = false;

    $dataFormatadaManhã = $ano . '-' . $mes . '-' . $i . ' 00:00:00';
    $dataFormatadaTarde = $ano . '-' . $mes . '-' . $i . ' 12:00:00';

    $dataManhã = date('Y-m-d H:i:s', strtotime($dataFormatadaManhã));
    $dataTarde = date('Y-m-d H:i:s', strtotime($dataFormatadaTarde));

    foreach ($viagens as $viagem) {
      $horaSaida = date('H:i:s', strtotime($viagem['data_saida']));
      $horaChegada = date('H:i:s', strtotime($viagem['data_chegada']));

      if ($horaSaida <= $dataManhã && $horaChegada >= $dataManhã) {
        $viagem_manhã_encontrada = true;
        break;
      }

      if ($horaSaida <= $dataTarde && $horaChegada >= $dataTarde) {
        $viagem_tarde_encontrada = true;
        break;
      }
    }

    if ($viagem_manhã_encontrada) {
      echo "<td style='background-color: #00FF00'>Em viagem</td>";
    } else {
      echo "<td style='background-color: #FF0000'>Em casa</td>";
    }

    if ($viagem_tarde_encontrada) {
      echo "<td style='background-color: #00FF00'>Em viagem</td>";
    } else {
      echo "<td style='background-color: #FF0000'>Em casa</td>";
    }
  }

  echo "</tr>";
}

