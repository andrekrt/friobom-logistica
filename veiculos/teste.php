<?php

include '../conexao-on.php';

// Mês e ano selecionados
$mes = 6; // Exemplo de mês (julho)
$ano = 2023; // Exemplo de ano


try {
    
    // Obter todas as datas do mês selecionado
    $primeiroDia = $ano . '-' . $mes . '-01';
    $ultimoDia = date('Y-m-t', strtotime($primeiroDia));
    $intervalo = new DatePeriod(new DateTime($primeiroDia), new DateInterval('P1D'), new DateTime($ultimoDia));
    
    // Consulta para obter todos os veículos cadastrados
    $sqlVeiculos = "SELECT placa_veiculo FROM viagem GROUP BY placa_veiculo";
    $stmtVeiculos = $db->query($sqlVeiculos);
    $veiculos = $stmtVeiculos->fetchAll(PDO::FETCH_COLUMN);

    $teste = $db->prepare("SELECT placa_veiculo, data_saida, data_chegada FROM viagem");
    
    // Construir a tabela
    echo '<table border="1">';
    
    // Primeira linha com as datas
    echo '<tr><th>Veículo</th>';
    foreach ($intervalo as $data) {
        echo '<th>' . $data->format('Y-m-d') . '</th>';
    }
    echo '</tr>';
    
    // Demais linhas com os veículos e marcação de viagem
    foreach ($veiculos as $veiculo) {
        echo '<tr>';
        echo "<td>$veiculo</td>";
        $teste = $db->prepare("SELECT placa_veiculo, data_saida, data_chegada FROM viagem WHERE MONTH(data_saida) <= $mes AND MONTH(data_chegada) >= $mes AND YEAR(data_saida) = $ano AND placa_veiculo = :veiculo");
        $teste->bindValue(':veiculo', $veiculo);
        $teste->execute();
        $dados  = $teste->fetchAll();
        // print_r($dados);
        // echo "<br><br>";
        foreach ($intervalo as $data) {
            $dataFormatada = $data->format('Y-m-d');
            
            foreach($dados as $viagem){
                if(count($viagem)>0){
                    if($dataFormatada>=$viagem['data_saida'] && $dataFormatada<=$viagem['data_chegada']){
                        $status = "SIM";
                    }else{
                        $status="NÃO";
                    }
                    
                }else{
                    $status="NÃO";
                }
                echo '<td>' . $status . '</td>';
            }

            // Consulta para verificar se o veículo estava viajando nesse dia
            // $sqlViagem = "SELECT COUNT(*) AS total FROM viagem WHERE (:dataAtual1 >= DATE(data_saida) AND :dataAtual2 <=DATE(data_chegada)) AND placa_veiculo = :veiculo";
            // $stmtViagem = $db->prepare($sqlViagem);
            // $stmtViagem->bindParam(':veiculo', $veiculo);
            // $stmtViagem->bindParam(':dataAtual1', $dataFormatada);
            // $stmtViagem->bindParam(':dataAtual2', $dataFormatada);
            // $stmtViagem->execute();
            
            // $resultado = $stmtViagem->fetch(PDO::FETCH_ASSOC);
            // $total = $resultado['total'];
            
            // // Marcação de viagem
            
        }
        
        echo '</tr>';
    }
    
    echo '</table>';
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>
