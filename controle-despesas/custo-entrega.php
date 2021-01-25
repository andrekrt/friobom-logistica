<?php 

session_start();
require("../conexao.php");
$i = 0;

$consulta = $db->query("SELECT * FROM viagem");
$qtd = $consulta->rowCount();

    $sql = $db->query("SELECT * FROM viagem");
    $dados = $sql->fetchAll();
    foreach($dados as $dado){
        $id = $dado['iddespesas'];
        $valorCombustivel = $dado['valor_total_abast'];
        $vlDiariaMotorista = $dado['diarias_motoristas'];
        $vlDiariaAjudante = $dado['diarias_ajudante'];
        $vlDiariaChapa = $dado['diarias_chapa'];
        $diasMotorista = $dado['dias_motorista'];
        $diasAjudante = $dado['dias_ajudante'];
        $diasChapa = $dado['dias_chapa'];
        $outrosGastosAjudante = $dado['outros_gastos_ajudante'];
        $tomada = $dado['tomada'];
        $descarga = $dado['descarga'];
        $travessia = $dado['travessia'];
        $outrosServicos = $dado['outros_servicos'];
        $qtdEntregas = $dado['qtd_entregas'];
        
        $custoEntrega = ((63.41*$diasMotorista) + $valorCombustivel + ($vlDiariaMotorista * $diasMotorista) + ($vlDiariaAjudante * $diasAjudante) + ($vlDiariaChapa * $diasChapa) + $outrosGastosAjudante + $tomada + $descarga + $travessia + $outrosServicos ) / $qtdEntregas;

        $atualiza = $db->query("UPDATE viagem SET custo_entrega = '$custoEntrega' WHERE iddespesas = '$id' ");
    }

?>