<?php

function checklist($carregamento){
    include '../conexao.php';
    $check = $db->prepare("SELECT * FROM checklist_apps_retorno02 LEFT JOIN checklist_apps ON checklist_apps_retorno02.checksaida = checklist_apps.id  WHERE carregamento_ret = :carregamento");
    $check->bindValue(':carregamento', $carregamento);
    $check->execute();
    if($check->rowCount()>0){
        $checks=$check->fetch();
        if(
            $checks['cabine']==$checks['cabine'] &&
            $checks['retrovisores']==$checks['retrovisores_ret'] &&
            $checks['parabrisa']==$checks['parabrisa_ret'] &&
            $checks['quebra_sol']==$checks['quebra_sol_ret'] &&
            $checks['bordo']==$checks['bordo_ret'] &&
            $checks['buzina']==$checks['buzina_ret'] &&
            $checks['cinto']==$checks['cinto_ret'] &&
            $checks['extintor']==$checks['extintor_ret'] &&
            $checks['triangulo']==$checks['triangulo_ret'] &&
            $checks['macaco']==$checks['macaco_ret'] &&
            $checks['tanque']==$checks['tanque_ret'] &&
            $checks['janelas']==$checks['janelas_ret'] &&
            $checks['banco']==$checks['banco_ret'] &&
            $checks['porta']==$checks['porta_ret'] &&
            $checks['cambio']==$checks['cambio_ret'] &&
            $checks['seta']==$checks['seta_ret'] &&
            $checks['luz_freio']==$checks['luz_freio_ret'] &&
            $checks['luz_re']==$checks['luz_re_ret'] &&
            $checks['alerta']==$checks['alerta_ret'] &&
            $checks['luz_teto']==$checks['luz_teto_ret'] &&
            $checks['faixas']==$checks['faixas_ret'] &&
            $checks['farol_dianteiro']==$checks['farol_dianteiro_ret']&& 
            $checks['farol_traseiro']==$checks['farol_traseiro_ret']&& 
            $checks['farol_neblina']==$checks['farol_neblina_ret']&& 
            $checks['farol_alto']==$checks['farol_alto_ret'] &&
            $checks['painel']==$checks['painel_ret'] &&
            $checks['rodas']==$checks['rodas_ret'] &&
            $checks['pneus']==$checks['pneus_ret'] &&
            $checks['estepe']==$checks['estepe_ret'] &&
            $checks['molas']==$checks['molas_ret'] &&
            $checks['cabo_forca']==$checks['cabo_forca_ret'] &&
            $checks['refrigeracao']==$checks['refrigeracao_ret'] &&
            $checks['ventilador']==$checks['ventilador_ret']
        ){
            $variavelCheck = 1;
        }else{
            $variavelCheck = 0;
        }

    }else{
        $variavelCheck = "Nenhum Check-List Encontrado para o carregamento $carregamento";
    }

    return $variavelCheck;
}

function mediaConsumo($carregamento){
    include '../conexao.php';
    $media = $db->prepare("SELECT media_comtk, meta_combustivel FROM viagem LEFT JOIN veiculos ON viagem.placa_veiculo=veiculos.placa_veiculo WHERE num_carregemento = :caregamento");
    $media->bindValue(':caregamento', $carregamento);
    $media->execute();
    if($media->rowCount()>0){
        $media = $media->fetch(PDO::FETCH_ASSOC);
        if($media['media_comtk']>=$media['meta_combustivel']){
            $percMedia = 1;
        }else{
            $percMedia=0;
        }
        
    }else{
        $percMedia = "Despesas ainda não lançada para o carregamento $carregamento";
    }

    return $percMedia;
}
