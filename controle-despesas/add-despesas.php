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
    $filial = $_SESSION['filial'];
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

    // data e hora atual
    $dataHoraAtual = date('Y-m-d H:i:s');
    
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
    $nf1Abast = filter_input(INPUT_POST, 'nf1Abast');
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
    $nf2Abast = filter_input(INPUT_POST, 'nf2Abast');
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
    $nf3Abast = filter_input(INPUT_POST, 'nf3Abast');
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

    $db->beginTransaction();

    try{

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
        $diasEmRota= number_format($diferencaDias->days+($diferencaDias->h/24),2) ;

        if($diasEmRota>9){
            $_SESSION['msg'] = 'Dias em Rota acima de 8 dias!';
            $_SESSION['icon']='warning';
            header("Location: form-lancar-despesas.php");
            exit();
        }

        $salario = $db->prepare("SELECT salario FROM motoristas WHERE cod_interno_motorista = :codMotorista");
        $salario->bindValue('codMotorista', $codMotorista);
        $salario->execute();
        $salario = $salario->fetch();
        $salario = $salario['salario'];
        $custoEntrega = ((($salario/30)* $diasRotaMotorista)+ $valorTotalAbast+($diariaMotorista*$diasRotaMotorista)+($diariaAjudante*$diasRotaAjudante)+($diariaChapa*$diasRotaChapa)+$gastosAjudante+$tomada+$descarga+$travessia+$servicos) / $qtdEntregas;

        /* Consulta para saber se rota, veiculo e motorista estão cadastrados */
        $consultaVeiculo = $db->prepare("SELECT * FROM veiculos WHERE cod_interno_veiculo = :codVeiculo ");
        $consultaVeiculo->bindValue(':codVeiculo', $codVeiculo);
        $consultaVeiculo->execute();

        $consultaMotorista = $db->prepare("SELECT * FROM motoristas WHERE cod_interno_motorista = :codMot");
        $consultaMotorista->bindValue(':codMot', $codMotorista);
        $consultaMotorista->execute();

        $consultaRota = $db->prepare("SELECT * FROM rotas WHERE cod_rota = :codRota");
        $consultaRota->bindValue(':codRota', $codRota);
        $consultaRota->execute();

        // verificar se ja existe despesa nesse carregamento
        $sqlCarregamento = $db->prepare("SELECT * FROM viagem WHERE num_carregemento =:carregamento");
        $sqlCarregamento->bindValue(':carregamento', $numCarregamento);
        $sqlCarregamento->execute();
        if($sqlCarregamento->rowCount()>1){
            $_SESSION['msg'] = 'Já existe despesa com o carregamento '.$numCarregamento.'!';
            $_SESSION['icon']='warning';
            header("Location: form-lancar-despesas.php");
            exit();
        }
        
        if($consultaVeiculo->rowCount()>0 && $consultaMotorista->rowCount()>0 && $consultaRota->rowCount()>0){
            $sql = $db->prepare("INSERT INTO viagem (cod_interno_veiculo, tipo_veiculo, placa_veiculo, cod_interno_motorista, nome_motorista, data_registro, data_carregamento, num_carregemento, data_saida, data_chegada, dias_em_rota, cod_rota, nome_rota, valor_transportado, valor_devolvido, valor_liquido, qtd_entregas, num_carga, peso_carga, km_saida, hr_tk_saida, km_abast1, hr_tk_abast1, lt_abast1, valor_abast1, km_perc1, km_pec1_tk, kmPorLtSemTk, km_abast2, hr_tk_abast2, lt_abast2, valor_abast2, km_perc2, km_pec2_tk_, kmPorLtSemTk2, km_abast3, hr_tk_abast3, lt_abast3, valor_abast3, km_perc3, km_pec3_tk, kmPorLtSemTk3, km_abast4, hr_tk_abast4, lt_abast4, valor_abast4, km_perc4, km_perc4_tk, kmPorLtSemTk4, km_rodado, km_final, litros, mediaSemTk, consumo_tk, media_comtk, valor_total_abast, diarias_motoristas, dias_motorista, diarias_ajudante, dias_ajudante, diarias_chapa, dias_chapa,  outros_gastos_ajudante,  tomada, descarga, travessia, outros_servicos, nome_ajudante, chapa01, chapa02, localAbast1, localAbast2, localAbast3, localAbast4, custo_entrega, nota_carga, obs_carga, idusuarios, nf1abast, nf2abast, nf3abast, cidade_base, filial) VALUES (:codVeiculo, :tipoVeiculo, :placaVeiculo, :codMotorista, :nomeMotorista, :data_registro, :dataCarragemento, :numCarregamento, :dataSaida, :dataChegada, :diasEmRota, :codRota, :rota, :vlTransp, :vlDev, :vlLiq, :qtdEntregas, :cargas, :pesoCarga, :kmSaida, :hrTkSaida, :km1Abast, :hrTk1Abast, :lt1Abast, :vl1Abast, :km1Perc, :tk1Perc, :kmPorLtSemtK, :km2Abast, :hrTk2Abast, :lt2Abast, :vl2Abast, :km2Perc, :tk2Perc, :kmPorLtSemtK2, :km3Abast, :hrTk3Abast, :lt3Abast, :vl3Abast, :km3Perc, :tk3Perc, :kmPorLtSemtK3, :km4Abast, :hrTk4Abast, :lt4Abast, :vl4Abast, :km4Perc, :kmPorLtSemtK4, :kmPorLtSemtK4, :kmRodado, :kmFinal, :litrosTotal, :mediaSemTk, :consumoTotalTk, :mediaTk, :valorTotalAbast, :diariaMotorista, :diasRotaMotorista, :diariaAjudante, :diasRotaAjudante, :diariaChapa, :diasRotaChapa, :gastosAjudante, :tomada, :descarga, :travessia, :servicos, :nomeAjudante, :chapa01, :chapa02, :localAbast1, :localAbast2, :localAbast3, :localAbast4, :custoEntrega, :classificacao, :obs, :idUsuario, :nf1Abast, :nf2Abast, :nf3Abast, :cidadeBase,:filial)");
            $sql->bindValue(':codVeiculo', $codVeiculo);
            $sql->bindValue(':tipoVeiculo', $tipoVeiculo);
            $sql->bindValue(':placaVeiculo', $placaVeiculo);
            $sql->bindValue(':codMotorista', $codMotorista);
            $sql->bindValue(':nomeMotorista', $nomeMotorista);
            $sql->bindValue(':data_registro', $dataHoraAtual);
            $sql->bindValue(':dataCarragemento', $dataCarragemento);
            $sql->bindValue(':numCarregamento', $numCarregamento);
            $sql->bindValue(':dataSaida', $dataSaida);
            $sql->bindValue(':dataChegada', $dataChegada);
            $sql->bindValue(':diasEmRota', $diasEmRota);
            $sql->bindValue(':codRota', $codRota);
            $sql->bindValue(':rota', $rota);
            $sql->bindValue(':vlTransp', $vlTransp);
            $sql->bindValue(':vlDev', $vlDev);
            $sql->bindValue(':vlLiq', $vlLiq);
            $sql->bindValue(':qtdEntregas', $qtdEntregas);
            $sql->bindValue(':cargas', $cargas);
            $sql->bindValue(':pesoCarga', $pesoCarga);
            $sql->bindValue(':kmSaida', $kmSaida);
            $sql->bindValue(':hrTkSaida', $hrTkSaida);
            $sql->bindValue(':km1Abast', $km1Abast);
            $sql->bindValue(':hrTk1Abast', $hrTk1Abast);
            $sql->bindValue(':lt1Abast', $lt1Abast);
            $sql->bindValue(':vl1Abast', $vl1Abast);
            $sql->bindValue(':km1Perc', $km1Perc);
            $sql->bindValue(':tk1Perc', $tk1Perc);
            $sql->bindValue(':kmPorLtSemtK', $kmPorLtSemtK);
            
            $sql->bindValue(':km2Abast', $km2Abast);
            $sql->bindValue(':hrTk2Abast', $hrTk2Abast);
            $sql->bindValue(':lt2Abast', $lt2Abast);
            $sql->bindValue(':vl2Abast', $vl2Abast);
            $sql->bindValue(':km2Perc', $km2Perc);
            $sql->bindValue(':tk2Perc', $tk2Perc);
            $sql->bindValue(':kmPorLtSemtK2', $kmPorLtSemtK2);

            $sql->bindValue(':km3Abast', $km3Abast);
            $sql->bindValue(':hrTk3Abast', $hrTk3Abast);
            $sql->bindValue(':lt3Abast', $lt3Abast);
            $sql->bindValue(':vl3Abast', $vl3Abast);
            $sql->bindValue(':km3Perc', $km3Perc);
            $sql->bindValue(':tk3Perc', $tk3Perc);
            $sql->bindValue(':kmPorLtSemtK3', $kmPorLtSemtK3);

            $sql->bindValue(':km4Abast', $km4Abast);
            $sql->bindValue(':hrTk4Abast', $hrTk4Abast);
            $sql->bindValue(':lt4Abast', $lt4Abast);
            $sql->bindValue(':vl4Abast', $vl4Abast);
            $sql->bindValue(':km4Perc', $km4Perc);
            $sql->bindValue(':tk4Perc', $tk4Perc);
            $sql->bindValue(':kmPorLtSemtK4', $kmPorLtSemtK4);

        
            $sql->bindValue(':kmRodado', $kmRodado);
            $sql->bindValue(':kmFinal', $kmFinal);
            $sql->bindValue(':litrosTotal', $litrosTotal);
            $sql->bindValue(':mediaSemTk', $mediaSemTk);
            $sql->bindValue(':consumoTotalTk', $consumoTotalTk);
            $sql->bindValue(':mediaTk', $mediaTk);
            $sql->bindValue(':valorTotalAbast', $valorTotalAbast);
            $sql->bindValue(':diariaMotorista', $diariaMotorista);
            $sql->bindValue(':diasRotaMotorista', $diasRotaMotorista);
            $sql->bindValue(':diariaAjudante', $diariaAjudante);
            $sql->bindValue(':diasRotaAjudante', $diasRotaAjudante);
            $sql->bindValue(':diariaChapa', $diariaChapa);
            $sql->bindValue(':diasRotaChapa', $diasRotaChapa);
            $sql->bindValue(':gastosAjudante', $gastosAjudante);
            $sql->bindValue(':tomada', $tomada);
            $sql->bindValue(':descarga', $descarga);
            $sql->bindValue(':travessia', $travessia);
            $sql->bindValue(':servicos', $servicos);
            $sql->bindValue(':nomeAjudante', $nomeAjudante);
            $sql->bindValue(':chapa01', $chapa01);
            $sql->bindValue(':chapa02', $chapa02);
            $sql->bindValue(':localAbast1', $localAbast1);
            $sql->bindValue(':localAbast2', $localAbast2);
            $sql->bindValue(':localAbast3', $localAbast3);
            $sql->bindValue(':localAbast4', $localAbast4);
            $sql->bindValue(':custoEntrega', $custoEntrega);
            $sql->bindValue(':classificacao', $classificacao);
            $sql->bindValue(':obs', $obs);
            $sql->bindValue(':idUsuario', $idUsuario);
            $sql->bindValue(':nf1Abast', $nf1Abast);
            $sql->bindValue(':nf2Abast', $nf2Abast);
            $sql->bindValue(':nf3Abast', $nf3Abast);
            $sql->bindValue(':cidadeBase', $cidadeBase);
            $sql->bindValue(':filial', $filial);
            $sql->execute();

            $ultimoId = $db->lastInsertId();

            $kmAtual = $db->prepare("UPDATE veiculos SET km_atual = :kmFinal WHERE cod_interno_veiculo = :codVeiculo ");
            $kmAtual->bindValue(':kmFinal', $kmFinal);
            $kmAtual->bindValue(':codVeiculo', $codVeiculo);
            $kmAtual->execute();

            $hrTkAtual = $db->prepare("UPDATE thermoking SET hora_atual = :hrTkAtual WHERE veiculo = :veiculo");
            $hrTkAtual->bindValue(':hrTkAtual', $hrTk4Abast);
            $hrTkAtual->bindValue(':veiculo', $codVeiculo);
            $hrTkAtual->execute();

            // calculoTk($codVeiculo);

            if(!empty($_FILES['imagem']['name'][0])){
                $pasta = "uploads/".$ultimoId;
                mkdir($pasta,0755);
                $destino =$pasta."/".$imagem;
                $mover = move_uploaded_file($_FILES['imagem']['tmp_name'],$destino);   
            }

            $db->commit();

            $_SESSION['msg'] = 'Despesa Lançada com Sucesso';
            $_SESSION['icon']='success';

        }else{
            $_SESSION['msg'] = 'Veículo, Motorista ou Rota não cadastrado!';
            $_SESSION['icon']='warning';
            header("Location: form-lancar-despesas.php");
            exit();
        }
        
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Despesa';
        $_SESSION['icon']='error';
    }    

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: despesas.php");
exit();
?>