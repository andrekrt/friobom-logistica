<?php 

function devolucoes($carregamento){
    include "../conexao-oracle.php";
    //pegando qtd de devoluções
    $qtdDev = $dbora->prepare("SELECT COUNT(DISTINCT codcli) as qtdDev FROM friobom.pcnfsaid WHERE numcar=:carreg AND coddevol IS NOT NULL");
    $qtdDev->bindValue(':carreg', $carregamento);
    if($qtdDev->execute()){
        $qtdDev=$qtdDev->fetch();
        $qtdDev=$qtdDev['QTDDEV'];
    }else{
        print_r($qtdDev->errorInfo());
    }
    
    return $qtdDev;

    //testando nova forma
    // foreach($carregamentos as $carregamento){
    //     $qtdDev = $dbora->prepare("SELECT COUNT(DISTINCT codcli) as qtdDev FROM friobom.pcnfsaid WHERE numcar=:carreg AND coddevol IS NOT NULL");
    //     $qtdDev->bindValue(':carreg', $carregamento['num_carregemento']);
    //     if($qtdDev->execute()){
    //         $qtdDev= $qtdDev->fetch();
    //         $qtdDev = $qtdDev['QTDDEV'];
    //     }else{  
    //         $qtdDev = $qtdDev->errorInfo();
    //     }
        
    //     $resultado[]=$qtdDev;
        
    // }

    // return $resultado;

}

function mauUsoFusion($carregamento){
    include '../conexao-on.php';
    //qtd de ocorrencias mau uso do fusion
    $qtdMalUso = $db->prepare("SELECT * FROM ocorrencias WHERE num_carregamento = :carregamento AND tipo_ocorrencia = :ocorrencia");
    $qtdMalUso->bindValue(':carregamento', $carregamento);
    $qtdMalUso->bindValue(':ocorrencia', "Mau Uso do Fusion");
    $qtdMalUso->execute();
    $qtdMalUso=$qtdMalUso->rowCount();
    if($qtdMalUso==0){
        $percFusion = 1;
    }else{
        $percFusion=0;
    }

    $fusion['qtd']=$qtdMalUso;
    $fusion['percentual']=$percFusion;

    return $fusion;
}

function checklist($carregamento){
    include '../conexao-on.php';
    $check = $db->prepare("SELECT * FROM checklist_apps_retorno02 LEFT JOIN checklist_apps ON checklist_apps_retorno02.checksaida = checklist_apps.id  WHERE carregamento_ret = :carregamento");
    $check->bindValue(':carregamento', $carregamento);
    if($check->execute()){
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
        print_r($check->errorInfo());
    }

    return $variavelCheck;
}

function ocorrenciasVel($carregamento){
    include '../conexao-on.php';
    //qtd de ocoorrencias por velocidade
    $velExc = $db->prepare("SELECT * FROM ocorrencias WHERE num_carregamento=:carregamento AND tipo_ocorrencia=:ocorrencia");
    $velExc->bindValue(':carregamento', $carregamento);
    $velExc->bindValue(':ocorrencia', "Velocidade Excedida");
    $velExc->execute();
    $qtdvelExc = $velExc->rowCount();
    if($qtdvelExc==0){
        $percVel = 1;
    }else{
        $percVel = 0;
    }

    return $percVel;
}
?>