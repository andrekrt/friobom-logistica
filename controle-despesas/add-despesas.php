<?php

use DeepCopy\Filter\Filter;

session_start();
require("../conexao.php");
include("../thermoking/funcao.php");

$idModudulo = 7;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idUsuario = $_SESSION['idUsuario'];

    $codVeiculo = filter_input(INPUT_POST,'codVeiculo');
    $tipoVeiculo = filter_input(INPUT_POST, 'tipoVeiculo');
    $placaVeiculo = filter_input(INPUT_POST, 'placaVeiculo');
    $codMotorista = filter_input(INPUT_POST, 'codMotorista');
    $nomeMotorista = filter_input(INPUT_POST, 'motorista');
    $dataCarragemento = filter_input(INPUT_POST, 'dataCarregamento');
    $dataChegada = filter_input(INPUT_POST, 'dataChegada');
    $dataSaida = filter_input(INPUT_POST, 'dataSaida');
    $classificacao = filter_input(INPUT_POST, 'classificacao');
    $imagem = $_FILES['imagem']['name']?$_FILES['imagem']['name']:null;;
    $obs = filter_input(INPUT_POST, 'obs');

    //calculo de diferença de datas
    $dataFinial = new DateTime($dataChegada);
    $dataInicial = new DateTime($dataSaida);
    $diasEmRota = $dataFinial->diff($dataInicial);
    $diasEmRota = $diasEmRota->format("%d");
    
    $numCarregamento = filter_input(INPUT_POST, 'nCarregamento');
    $codRota = filter_input(INPUT_POST,'codRota');
    $rota = filter_input(INPUT_POST, 'rota');
    $vlTransp = floatval( str_replace(",", ".",filter_input(INPUT_POST, 'vlTransp')));
    $vlDev = floatval( str_replace(",",".", filter_input(INPUT_POST, 'vlDev')));
    $vlLiq = $vlTransp - $vlDev;
    $qtdEntregas = filter_input(INPUT_POST, 'qtdEntrega');
    $cargas = filter_input(INPUT_POST, 'nCarga');
    $pesoCarga = str_replace(",",".",filter_input(INPUT_POST, 'pesoCarga')) ;
    $kmSaida = intval(filter_input(INPUT_POST, 'kmSaida'));
    $hrTkSaida = filter_input(INPUT_POST, 'hrTkSaida')?filter_input(INPUT_POST, 'hrTkSaida'):0;
    
    
    /**1º Abastecimento */
    $localAbast1 = filter_input(INPUT_POST, 'local1Abast');
    $km1Abast = intval(filter_input(INPUT_POST, 'km1Abast'));
    $hrTk1Abast = filter_input(INPUT_POST, 'hrKm1Abast')?filter_input(INPUT_POST, 'hrKm1Abast'):0;
    $lt1Abast = floatval( str_replace(",",".", filter_input(INPUT_POST, 'lt1Abast')));
    $vl1Abast = floatval( str_replace(",",".",filter_input(INPUT_POST, 'vl1Abast')) );
    if($km1Abast==0 || $km1Abast==" "){
        $km1Perc = 0;
    }else{
        $km1Perc = $km1Abast - $kmSaida;
    }
    if($hrTk1Abast==0 || $hrTk1Abast==" "){
        $tk1Perc = 0;
    }else{
        $tk1Perc = ($hrTk1Abast-$hrTkSaida)*2;
    }
    
    $kmPorLtSemtK;
    if($hrTkSaida==0 || $hrTkSaida == " " || $km1Perc==0){
        $kmPorLtSemtK =0;
    }else{
        $kmPorLtSemtK = number_format($km1Perc/($lt1Abast-$tk1Perc), "2") ;
    }

    /*2º Abastecimento */
    $localAbast2 = filter_input(INPUT_POST, 'local2Abast');
    $km2Abast = intval(filter_input(INPUT_POST, 'km2Abast'));
    $hrTk2Abast =intval(filter_input(INPUT_POST, 'hrKm2Abast'));
    $lt2Abast = filter_input(INPUT_POST, 'lt2Abast')?floatval( str_replace(",",".", filter_input(INPUT_POST, 'lt2Abast'))):0;
    $vl2Abast = floatval( str_replace(",",".",filter_input(INPUT_POST, 'vl2Abast')) );
    if($km2Abast==0 || $km2Abast==" "){
        $km2Perc = 0;
    }else{
        $km2Perc = $km2Abast - $km1Abast;
    }
    if($hrTk2Abast==0 || $hrTk2Abast==" "){
        $tk2Perc = 0;
    }else{
        $tk2Perc = ($hrTk2Abast-$hrTk1Abast)*2;
    }
    
    $kmPorLtSemtK2;
    if($lt2Abast==" " || $lt2Abast == 0){
        $kmPorLtSemtK2 =0;
    }else{
        $kmPorLtSemtK2 = number_format($km2Perc/($lt2Abast-$tk2Perc),"2") ;
    }
    
    /* 3º abastecimento */
    $localAbast3 = filter_input(INPUT_POST, 'local3Abast');
    $km3Abast = intval(filter_input(INPUT_POST, 'km3Abast'));
    $hrTk3Abast = intval(filter_input(INPUT_POST, 'hrKm3Abast'));
    $lt3Abast = filter_input(INPUT_POST, 'lt3Abast')?floatval(str_replace(",",".", filter_input(INPUT_POST, 'lt3Abast'))):0;
    $vl3Abast = floatval( str_replace(",",".",filter_input(INPUT_POST, 'vl3Abast')) );
    if($km3Abast==0 || $km3Abast==" "){
        $km3Perc = 0;
    }else{
        $km3Perc = $km3Abast - $km2Abast;
    }
    if($hrTk3Abast==0 || $hrTk3Abast==" "){
        $tk3Perc = 0;
    }else{
        $tk3Perc = ($hrTk3Abast-$hrTk2Abast)*2;
    }
    
    $kmPorLtSemtK3;
    if($lt3Abast==" " || $lt3Abast == 0){
        $kmPorLtSemtK3 =0;
    }else{
        $kmPorLtSemtK3 = number_format($km3Perc/($lt3Abast-$tk3Perc),"2" );
    }

    /* 4º abastecimento */
    $localAbast4 = filter_input(INPUT_POST, 'local4Abast');
    $km4Abast = intval(filter_input(INPUT_POST, 'km4Abast'));
    $hrTk4Abast = intval(filter_input(INPUT_POST, 'hrKm4Abast'));
    $lt4Abast = filter_input(INPUT_POST, 'lt4Abast')?floatval(str_replace(",",".", filter_input(INPUT_POST, 'lt4Abast'))):0;
    $vl4Abast = floatval( str_replace(",",".",filter_input(INPUT_POST, 'vl4Abast')) );
    if($km4Abast==0 || $km4Abast==" "){ 
        $km4Perc = 0;
    }else{
        if($km1Abast==0){
           $km4Perc = $km4Abast-$kmSaida;
        }elseif($km2Abast==0){
            $km4Perc = $km4Abast-$km1Abast;
        }elseif($km3Abast==0){
            $km4Perc = $km4Abast-$km2Abast;
        }else{
            $km4Perc = $km4Abast - $km3Abast;
        }
        
    }
    $tk4Perc = ($hrTk4Abast-$hrTk3Abast)*2;
    $kmPorLtSemtK4 =0;
    /*if($lt4Abast==" " || $lt4Abast == 0){
        $kmPorLtSemtK4 =0;
    }else{
        $kmPorLtSemtK4 = number_format($km4Perc/($lt4Abast-$tk4Perc), "2")."<br>";
    }*/

    $percTk = $hrTk4Abast-$hrTkSaida;
    $kmRodado = $km1Perc + $km2Perc + $km3Perc + $km4Perc;
    $kmFinal = $kmSaida+$kmRodado;
    $litrosTotal = $lt1Abast + $lt2Abast + $lt3Abast + $lt4Abast;
    $mediaSemTk = number_format($kmRodado/$litrosTotal,"2", ".","");
    $consumoTotalTk = $percTk*2;
    $mediaTk = number_format($kmRodado/ ($litrosTotal-$consumoTotalTk),2,".","");
    $valorTotalAbast = $vl1Abast + $vl2Abast + $vl3Abast + $vl4Abast;
    $diariaMotorista = str_replace(",", ".", filter_input(INPUT_POST, 'diariasMot')) ;
    $diasRotaMotorista = str_replace(",",".",filter_input(INPUT_POST, 'diasRota')) ;
    $diariaAjudante = str_replace(",", ".", filter_input(INPUT_POST, 'diariasAjud')) ;
    $diasRotaAjudante = str_replace(",",".",filter_input(INPUT_POST, 'diasRotaAjud')) ;
    $gastosAjudante = filter_input(INPUT_POST, 'gastosAjud')? str_replace(",", ".",filter_input(INPUT_POST, 'gastosAjud')) :0;
    $diariaChapa = str_replace(",", ".", filter_input(INPUT_POST, 'diariasChapa')) ;
    $diasRotaChapa = str_replace(",",".",filter_input(INPUT_POST, 'diasRotaChapa'));//diasRotaChapa
    $tomada = filter_input(INPUT_POST, 'tomada')? str_replace(",", ".",filter_input(INPUT_POST, 'tomada')) :0;
    $descarga = filter_input(INPUT_POST, 'descarga')? str_replace(",", ".",filter_input(INPUT_POST, 'descarga')) :0;
    $travessia = filter_input(INPUT_POST, 'travessia')? str_replace(",", ".",filter_input(INPUT_POST, 'travessia')) :0;
    $servicos = filter_input(INPUT_POST, 'servicos')? str_replace(",", ".",filter_input(INPUT_POST, 'servicos')) :0;
    $nomeAjudante = filter_input(INPUT_POST, 'nomeAjud');
    $chapa01 = filter_input(INPUT_POST, 'chapa1');
    $chapa02 = filter_input(INPUT_POST, 'chapa2');

    // echo "Código do Veículo: $codVeiculo<br>
    //  Tipo do Veículo: $tipoVeiculo<br>
    // Placa do Veículo: $placaVeiculo<br>
    // Código do Motorista: $codMotorista<br>
    // Nome do Motorista: $nomeMotorista<br>
    // Data do Carregamento: $dataCarragemento<br>
    // Número do Carregamento: $numCarregamento<br>
    // Código da Rota: $codRota<br>
    // Rota: $rota<br>
    // Valor Transportado: $vlTransp<br>
    // Valor Devolvido: $vlDev<br>
    // Valor Liquído $vlLiq<br>
    // Quantidade de Entregas: $qtdEntregas<br>
    // Carga: $cargas<br>
    // Peso da Carga: $pesoCarga<br>
    // Km de Saída: $kmSaida<br>
    // Hora TK de Saída:$hrTkSaida<br>
    // Km 1º Abastecimento: $km1Abast<br>
    // Hora TK 1° Abastecimento: $hrTk1Abast<br>
    // Litros 1º Abastecimento: $lt1Abast<br>
    // Valor 1º Abastecimento: $vl1Abast<br>
    // Km 1º Percusso: $km1Perc<br>
    // TK 1º Percusso: $tk1Perc<br>
    // Km/L S/TK: $kmPorLtSemtK<br><br>
    // Km 2º Abastecimento: $km2Abast<br>
    // Hora Tk 2º Abastecimento: $hrTk2Abast<br>
    // Litros 2º Abastecimento: $lt2Abast<br>
    // Valor 2º Abastecimento: $vl2Abast<br>
    // Km 2º Percusso: $km2Perc<br>
    // TK 2º Percusso: $tk2Perc<br>
    // Km/L S/TK: $kmPorLtSemtK2<br><br>
    // Km 3º Abastecimento: $km3Abast<br>
    // Hora TK 3º Abastecimento: $hrTk3Abast<br>
    // Litros 3º Abastecimento: $lt3Abast<br>
    // Valor 3º Abastecimento: $vl3Abast<br>
    // Km 3º Percusso: $km3Perc<br>
    // Tk 3º Percusso: $tk3Perc<br>
    // Km/L S/TK: $kmPorLtSemtK3<br><br>
    // Km 4º Abastecimento: $km4Abast<br>
    // Hora Tk 4º Abastecimento: $hrTk4Abast<br>
    // Litros 4º Abastecimento: $lt4Abast<br>
    // Valor 4º Abastecimento: $vl4Abast<br>
    // Km 4º Percusso: $km4Perc<br>
    // TK 4º Percusso: $tk4Perc<br>
    // Km/L S/TK: $kmPorLtSemtK4<br><br>
    // Km Rodada: $kmRodado<br>
    // Km Final: $kmFinal<br>
    // Litros Total: $litrosTotal<br>
    // Media Sem TK: $mediaSemTk<br>
    // Consumo Total Tk: $consumoTotalTk<br>
    // Media Tk: $mediaTk<br>
    // Valor Total Abastecimento: $valorTotalAbast<br>
    // Diaria Motorista: $diariaMotorista<br>
    // Diaria Ajudante: $diariaAjudante<br>
    // Gastos Ajudante: $gastosAjudante<br>
    // Tomada: $tomada<br>
    // Descarga: $descarga<br>
    // Travessia: $travessia<br>
    // Serviço: $servicos<br>
    // Nome Ajudante: $nomeAjudante<BR><BR>";

    $salario = $db->prepare("SELECT salario FROM motoristas WHERE cod_interno_motorista = :codMotorista");
    $salario->bindValue('codMotorista', $codMotorista);
    $salario->execute();
    $salario = $salario->fetch();
    $salario = $salario['salario'];
    $custoEntrega = ((($salario/30)* $diasRotaMotorista)+ $valorTotalAbast+($diariaMotorista*$diasRotaMotorista)+($diariaAjudante*$diasRotaAjudante)+($diariaChapa*$diasRotaChapa)+$gastosAjudante+$tomada+$descarga+$travessia+$servicos) / $qtdEntregas;

    /* Consulta para saber se rota, veiculo e motorista estão cadastrados */
    $consultaVeiculo = $db->query("SELECT * FROM veiculos WHERE cod_interno_veiculo = '$codVeiculo' ");
    $consultaMotorista = $db->query("SELECT * FROM motoristas WHERE cod_interno_motorista = '$codMotorista'");
    $consultaRota = $db->query("SELECT * FROM rotas WHERE cod_rota = '$codRota'");
    
   if($consultaVeiculo->rowCount()>0 && $consultaMotorista->rowCount()>0 && $consultaRota->rowCount()>0){
        $sql = $db->query("INSERT INTO viagem (cod_interno_veiculo, tipo_veiculo, placa_veiculo, cod_interno_motorista, nome_motorista, data_carregamento, num_carregemento, data_saida, data_chegada, dias_em_rota, cod_rota, nome_rota, valor_transportado, valor_devolvido, valor_liquido, qtd_entregas, num_carga, peso_carga, km_saida, hr_tk_saida, km_abast1, hr_tk_abast1, lt_abast1, valor_abast1, km_perc1, km_pec1_tk, kmPorLtSemTk, km_abast2, hr_tk_abast2, lt_abast2, valor_abast2, km_perc2, km_pec2_tk_, kmPorLtSemTk2, km_abast3, hr_tk_abast3, lt_abast3, valor_abast3, km_perc3, km_pec3_tk, kmPorLtSemTk3, km_abast4, hr_tk_abast4, lt_abast4, valor_abast4, km_perc4, km_perc4_tk, kmPorLtSemTk4, km_rodado, km_final, litros, mediaSemTk, consumo_tk, media_comtk, valor_total_abast, diarias_motoristas, dias_motorista, diarias_ajudante, dias_ajudante, diarias_chapa, dias_chapa,  outros_gastos_ajudante,  tomada, descarga, travessia, outros_servicos, nome_ajudante, chapa01, chapa02, localAbast1, localAbast2, localAbast3, localAbast4, custo_entrega, nota_carga, obs_carga, idusuarios) VALUES ('$codVeiculo', '$tipoVeiculo', '$placaVeiculo', '$codMotorista', '$nomeMotorista','$dataCarragemento', '$numCarregamento', '$dataSaida', '$dataChegada', '$diasEmRota', '$codRota', '$rota', '$vlTransp', '$vlDev', '$vlLiq', '$qtdEntregas', '$cargas', '$pesoCarga', '$kmSaida', '$hrTkSaida', '$km1Abast', '$hrTk1Abast', '$lt1Abast', '$vl1Abast', '$km1Perc', '$tk1Perc', '$kmPorLtSemtK', '$km2Abast', '$hrTk2Abast', '$lt2Abast', '$vl2Abast', '$km2Perc', '$tk2Perc', '$kmPorLtSemtK2', '$km3Abast', '$hrTk3Abast', '$lt3Abast', '$vl3Abast', '$km3Perc', '$tk3Perc', '$kmPorLtSemtK3', '$km4Abast', '$hrTk4Abast', '$lt4Abast', '$vl4Abast', '$km4Perc', '$percTk', '$kmPorLtSemtK4', '$kmRodado', '$kmFinal', '$litrosTotal', '$mediaSemTk', '$consumoTotalTk', '$mediaTk', '$valorTotalAbast', '$diariaMotorista', '$diasRotaMotorista', '$diariaAjudante', '$diasRotaAjudante', '$diariaChapa', '$diasRotaChapa', '$gastosAjudante', '$tomada', '$descarga', '$travessia', '$servicos', '$nomeAjudante', '$chapa01', '$chapa02', '$localAbast1', '$localAbast2', '$localAbast3', '$localAbast4', '$custoEntrega', '$classificacao', '$obs', '$idUsuario')");
        $ultimoId = $db->lastInsertId();

        $kmAtual = $db->prepare("UPDATE veiculos SET km_atual = :kmFinal WHERE cod_interno_veiculo = :codVeiculo ");
        $kmAtual->bindValue(':kmFinal', $kmFinal);
        $kmAtual->bindValue(':codVeiculo', $codVeiculo);
        $kmAtual->execute();

        $hrTkAtual = $db->prepare("UPDATE thermoking SET hora_atual = :hrTkAtual WHERE veiculo = :veiculo");
        $hrTkAtual->bindValue(':hrTkAtual', $hrTk4Abast);
        $hrTkAtual->bindValue(':veiculo', $codVeiculo);
        $hrTkAtual->execute();

        calculoTk($codVeiculo);

        if($sql){
            if(!empty($_FILES['imagem']['name'][0])){
                $pasta = "uploads/".$ultimoId;
                mkdir($pasta,0755);
                $destino =$pasta."/".$imagem;
                $mover = move_uploaded_file($_FILES['imagem']['tmp_name'],$destino);   
            }
            echo "<script> alert('Despesa Lançada!!')</script>";
            echo "<script> window.location.href='despesas.php' </script>";
            
        }else{
            print_r($db->errorInfo());
        }

    }else{
        echo "<script>alert('Veículo, Motorista ou Rota não cadastrado!');</script>";
        echo "<script>window.location.href='form-lancar-despesas.php'</script>";
    }

}else{
    header("Location: form-lancar-despesas.php");
}

?>