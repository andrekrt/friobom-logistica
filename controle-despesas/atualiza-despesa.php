<?php

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

    $idDespesa = filter_input(INPUT_POST,'idDespesa');
    $codVeiculo = filter_input(INPUT_POST,'codVeiculo');
    $tipoVeiculo = filter_input(INPUT_POST, 'tipoVeiculo');
    $placaVeiculo = filter_input(INPUT_POST, 'placaVeiculo');
    $codMotorista = filter_input(INPUT_POST, 'codMotorista');
    $nomeMotorista = filter_input(INPUT_POST, 'motorista');
    $dataCarragemento = filter_input(INPUT_POST, 'dataCarregamento');
    $dataChegada = filter_input(INPUT_POST, 'dataChegada');
    $dataSaida = filter_input(INPUT_POST, 'dataSaida');

    // verificar cidade base do veiculo para registrar no bd da viagem
    $sqlCidade = $db->prepare("SELECT cidade_base FROM veiculos WHERE placa_veiculo =:veiculo");
    $sqlCidade->bindValue(':veiculo', $placaVeiculo);
    $sqlCidade->execute();
    $cidadeBase = $sqlCidade->fetch();
    $cidadeBase = $cidadeBase['cidade_base'];

    //calculo de diferença de datas
    $dataFinial = new DateTime($dataChegada);
    $dataInicial = new DateTime($dataSaida);
    $diferencaDias = $dataFinial->diff($dataInicial);
    $diasEmRota= number_format($diferencaDias->days+($diferencaDias->h/24) + ($diferencaDias->i/1440),2) ;
    
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

    $kmRodado = $km1Perc + $km2Perc + $km3Perc + $km4Perc;
    $kmFinal = $kmSaida+$kmRodado;
    $litrosTotal = $lt1Abast + $lt2Abast + $lt3Abast + $lt4Abast;
    $mediaSemTk = number_format($kmRodado/$litrosTotal,"2");
    $consumoTotalTk = $tk4Perc-$hrTkSaida*2;
    $mediaTk = number_format($kmRodado/ ($litrosTotal-$consumoTotalTk),2);
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

    /*echo "ID: $idDespesa<br>
    Código do Veículo: $codVeiculo<br>
     Tipo do Veículo: $tipoVeiculo<br>
    Placa do Veículo: $placaVeiculo<br>
    Código do Motorista: $codMotorista<br>
    Nome do Motorista: $nomeMotorista<br>
    Data do Carregamento: $dataCarragemento<br>
    Número do Carregamento: $numCarregamento<br>
    Código da Rota: $codRota<br>
    Rota: $rota<br>
    Valor Transportado: $vlTransp<br>
    Valor Devolvido: $vlDev<br>
    Valor Liquído $vlLiq<br>
    Quantidade de Entregas: $qtdEntregas<br>
    Carga: $cargas<br>
    Peso da Carga: $pesoCarga<br>
    Km de Saída: $kmSaida<br>
    Hora TK de Saída:$hrTkSaida<br>
    Km 1º Abastecimento: $km1Abast<br>
    Hora TK 1° Abastecimento: $hrTk1Abast<br>
    Litros 1º Abastecimento: $lt1Abast<br>
    Valor 1º Abastecimento: $vl1Abast<br>
    Km 1º Percusso: $km1Perc<br>
    TK 1º Percusso: $tk1Perc<br>
    Km/L S/TK: $kmPorLtSemtK<br><br>
    Km 2º Abastecimento: $km2Abast<br>
    Hora Tk 2º Abastecimento: $hrTk2Abast<br>
    Litros 2º Abastecimento: $lt2Abast<br>
    Valor 2º Abastecimento: $vl2Abast<br>
    Km 2º Percusso: $km2Perc<br>
    TK 2º Percusso: $tk2Perc<br>
    Km/L S/TK: $kmPorLtSemtK2<br><br>
    Km 3º Abastecimento: $km3Abast<br>
    Hora TK 3º Abastecimento: $hrTk3Abast<br>
    Litros 3º Abastecimento: $lt3Abast<br>
    Valor 3º Abastecimento: $vl3Abast<br>
    Km 3º Percusso: $km3Perc<br>
    Tk 3º Percusso: $tk3Perc<br>
    Km/L S/TK: $kmPorLtSemtK3<br><br>
    Km 4º Abastecimento: $km4Abast<br>
    Hora Tk 4º Abastecimento: $hrTk4Abast<br>
    Litros 4º Abastecimento: $lt4Abast<br>
    Valor 4º Abastecimento: $vl4Abast<br>
    Km 4º Percusso: $km4Perc<br>
    TK 4º Percusso: $tk4Perc<br>
    Km/L S/TK: $kmPorLtSemtK4<br><br>
    Km Rodada: $kmRodado<br>
    Km Final: $kmFinal<br>
    Litros Total: $litrosTotal<br>
    Media Sem TK: $mediaSemTk<br>
    Consumo Total Tk: $consumoTotalTk<br>
    Media Tk: $mediaTk<br>
    Valor Total Abastecimento: $valorTotalAbast<br>
    Diaria Motorista: $diariaMotorista<br>
    Diaria Ajudante: $diariaAjudante<br>
    Gastos Ajudante: $gastosAjudante<br>
    Tomada: $tomada<br>
    Descarga: $descarga<br>
    Travessia: $travessia<br>
    Serviço: $servicos<br>
    Nome Ajudante: $nomeAjudante<BR><BR>";*/

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
        $sql = $db->prepare("UPDATE viagem SET cod_interno_veiculo = :codVeiculo, tipo_veiculo = :tipoVeiculo, placa_veiculo = :placaVeiculo, cod_interno_motorista = :codMotorista, nome_motorista = :nomeMotorista, data_carregamento = :dataCarregamento, num_carregemento = :numCarregamento, data_saida = :dataSaida, data_chegada = :dataChegada, dias_em_rota = :diasEmRota, cod_rota = :codRota, nome_rota = :nomeRota, valor_transportado = :valorTransportado, valor_devolvido = :valorDevolvido, valor_liquido = :valorLiquido, qtd_entregas = :qtdEntregas,  num_carga = :numCarga, peso_carga = :pesoCarga, km_saida = :kmSaida, hr_tk_saida = :hrTkSaida, km_abast1 = :kmAbast1, hr_tk_abast1 = :hrTkAbast1, lt_abast1 = :ltAbast1, valor_abast1 = :vlAbast1, km_perc1 = :kmPerc1, km_pec1_tk = :kmPerc1Tk, kmPorLtSemTk = :kmPorLtSemTK, km_abast2 = :kmAbast2, hr_tk_abast2 = :hrTkAbast2, lt_abast2 = :ltAbast2, valor_abast2 = :vlAbast2, km_perc2 = :kmPerc2, km_pec2_tk_ = :kmPerc2Tk, kmPorLtSemTk2 = :kmPorLtSemTk2, km_abast3 = :kmAbast3, hr_tk_abast3 = :hrTkAbast3, lt_abast3 = :ltAbast3, valor_abast3 = :vlAbast3, km_perc3 = :kmPerc3, km_pec3_tk = :kmPec3Tk, kmPorLtSemTk3 = :kmPorLtSemTk3, km_abast4 = :kmAbast4, hr_tk_abast4 = :hrTkAbast4, lt_abast4 = :ltAbast4, valor_abast4 = :vlAbast4, km_perc4 = :kmPerc4, km_perc4_tk = :kmPerc4Tk, kmPorLtSemTk4 = :kmPorLtSemTk4, km_rodado = :kmRodado, km_final = :kmFinal, litros = :litros, mediaSemTk = :mediaSemTk, consumo_tk = :consumoTk, media_comtk = :mediaComTk, valor_total_abast = :vlTotalAbast, diarias_motoristas = :diariasMotoristas, dias_motorista = :diasMotorista, diarias_ajudante = :diariasAjudante, dias_ajudante = :diasAjudante, diarias_chapa = :diariasChapa, dias_chapa = :diasChapa, outros_gastos_ajudante = :outrosGastosAjudante, tomada = :tomada, descarga = :descarga, travessia = :travessia, outros_servicos = :outrosServicos, nome_ajudante = :nomeAjudante, localAbast1 = :localAbast1, localAbast2 = :localAbast2, localAbast3 = :localAbast3, localAbast4 = :localAbast4, custo_entrega = :custoEntrega, idUsuarios = :idUsuario, cidade_base=:cidadeBase WHERE iddespesas = :idDespesa");
        $sql->bindValue(':codVeiculo', $codVeiculo);
        $sql->bindValue(':tipoVeiculo', $tipoVeiculo);
        $sql->bindValue(':placaVeiculo', $placaVeiculo);
        $sql->bindValue(':codMotorista', $codMotorista);
        $sql->bindValue(':nomeMotorista', $nomeMotorista);
        $sql->bindValue(':dataCarregamento', $dataCarragemento);
        $sql->bindValue(':numCarregamento', $numCarregamento);
        $sql->bindValue(':dataSaida', $dataSaida);
        $sql->bindValue(':dataChegada', $dataChegada);
        $sql->bindValue(':diasEmRota', $diasEmRota);
        $sql->bindValue(':codRota', $codRota);
        $sql->bindValue(':nomeRota', $rota);
        $sql->bindValue(':valorTransportado', $vlTransp);
        $sql->bindValue(':valorDevolvido', $vlDev);
        $sql->bindValue(':valorLiquido', $vlLiq);
        $sql->bindValue(':qtdEntregas', $qtdEntregas);
        $sql->bindValue(':numCarga', $cargas);
        $sql->bindValue(':pesoCarga', $pesoCarga);
        $sql->bindValue(':kmSaida', $kmSaida);
        $sql->bindValue(':hrTkSaida', $hrTkSaida);
        $sql->bindValue(':kmAbast1', $km1Abast);
        $sql->bindValue(':hrTkAbast1', $hrTk1Abast);
        $sql->bindValue(':ltAbast1', $lt1Abast);
        $sql->bindValue(':ltAbast1', $lt1Abast);
        $sql->bindValue(':vlAbast1', $vl1Abast);
        $sql->bindValue(':kmPerc1', $km1Abast);
        $sql->bindValue(':kmPerc1Tk', $tk1Perc );
        $sql->bindValue(':kmPorLtSemTK', $kmPorLtSemtK );
        $sql->bindValue(':kmAbast2', $km2Abast );
        $sql->bindValue(':hrTkAbast2', $hrTk2Abast );
        $sql->bindValue(':ltAbast2', $lt2Abast );
        $sql->bindValue(':vlAbast2', $vl2Abast );
        $sql->bindValue(':kmPerc2', $km2Perc );
        $sql->bindValue(':kmPerc2Tk', $tk2Perc );
        $sql->bindValue(':kmPorLtSemTk2', $kmPorLtSemtK2 );
        $sql->bindValue(':kmAbast3', $km3Abast );
        $sql->bindValue(':hrTkAbast3', $hrTk3Abast );
        $sql->bindValue(':ltAbast3', $lt3Abast );  
        $sql->bindValue(':vlAbast3', $vl3Abast );
        $sql->bindValue(':kmPerc3', $km3Perc );
        $sql->bindValue(':kmPec3Tk', $tk3Perc );
        $sql->bindValue(':kmPorLtSemTk3', $kmPorLtSemtK3 );
        $sql->bindValue(':kmAbast4', $km4Abast );
        $sql->bindValue(':hrTkAbast4', $hrTk4Abast );
        $sql->bindValue(':ltAbast4', $lt4Abast );
        $sql->bindValue(':vlAbast4', $vl4Abast );
        $sql->bindValue(':kmPerc4', $km4Perc );
        $sql->bindValue(':kmPerc4Tk', $tk4Perc );
        $sql->bindValue(':kmPorLtSemTk4', $kmPorLtSemtK4 );
        $sql->bindValue(':kmRodado', $kmRodado );
        $sql->bindValue(':kmFinal', $kmFinal );
        $sql->bindValue(':litros', $litrosTotal );
        $sql->bindValue(':mediaSemTk', $mediaSemTk );
        $sql->bindValue(':consumoTk', $consumoTotalTk );
        $sql->bindValue(':mediaComTk', $mediaTk );
        $sql->bindValue(':vlTotalAbast', $valorTotalAbast );
        $sql->bindValue(':diariasMotoristas', $diariaMotorista );
        $sql->bindValue(':diasMotorista', $diasRotaMotorista );
        $sql->bindValue(':diariasAjudante', $diariaAjudante );
        $sql->bindValue(':diasAjudante', $diasRotaAjudante );
        $sql->bindValue(':diariasChapa', $diariaChapa );
        $sql->bindValue(':diasChapa', $diasRotaChapa );
        $sql->bindValue(':outrosGastosAjudante', $gastosAjudante );
        $sql->bindValue(':tomada', $tomada );
        $sql->bindValue(':descarga', $descarga );
        $sql->bindValue(':travessia', $travessia );
        $sql->bindValue(':outrosServicos', $servicos );
        $sql->bindValue(':nomeAjudante', $nomeAjudante );
        $sql->bindValue(':localAbast1', $localAbast1 );
        $sql->bindValue(':localAbast2', $localAbast2 );  
        $sql->bindValue(':localAbast3', $localAbast3 ); 
        $sql->bindValue(':localAbast4', $localAbast4 );  
        $sql->bindValue(':custoEntrega', $custoEntrega );  
        $sql->bindValue(':idUsuario', $idUsuario );
        $sql->bindValue(':idDespesa', $idDespesa );
        $sql->bindValue(':cidadeBase', $cidadeBase);
        

        $kmAtual = $db->prepare("UPDATE veiculos SET km_atual = :kmFinal WHERE cod_interno_veiculo = :codVeiculo ");
        $kmAtual->bindValue(':kmFinal', $kmFinal);
        $kmAtual->bindValue(':codVeiculo', $codVeiculo);
        $kmAtual->execute();

        $hrTkAtual = $db->prepare("UPDATE thermoking SET hora_atual = :hrTkAtual WHERE veiculo = :veiculo");
        $hrTkAtual->bindValue(':hrTkAtual', $hrTk4Abast);
        $hrTkAtual->bindValue(':veiculo', $codVeiculo);
        $hrTkAtual->execute();

        calculoTk($codVeiculo);

        if($sql->execute()){
            header("Location: despesas.php");
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