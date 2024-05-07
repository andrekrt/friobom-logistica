<?php 
function registraMdfe(){
    require_once "../conexao-oracle.php";
    include_once "../conexao.php";

    try{
        $sqlPrepare = "SELECT 
        md.datahorageracao,nummdfe, md.obs, codmotorista, nome AS motorista , md.codveiculo, placa, situacaomdfe, (SELECT DESCRICAO
          FROM friobom.PCMENSAGEMMDFE
          WHERE CODMENSAGEM = md.SITUACAOMDFE) MENSAGEMSITUACAO
        FROM friobom.PCMANIFESTOELETRONICOC md
        LEFT JOIN FRIOBOM.pcempr e ON md.codmotorista = e.matricula
        LEFT JOIN FRIOBOM.pcveicul v ON md.codveiculo=v.codveiculo
        WHERE md.datahorageracao >= SYSDATE -3";

        $sql = $dbora->prepare($sqlPrepare);
        $sql->execute();
       
       
        while ($mdfe = $sql->fetch(PDO::FETCH_ASSOC)){
            $padraoCarga = "/CARGA: (\d+)/";
            $obs = stream_get_contents($mdfe['OBS']);
            preg_match_all($padraoCarga, $obs, $cargas);
            $numCargas = $cargas[1];
            $cargasText = implode(", ", $numCargas);   
            $msg = empty($mdfe['MENSAGEMSITUACAO'])?"Encerrado":$mdfe['MENSAGEMSITUACAO'];

            // pegar data de saida
            $sqlCarga = $dbora->prepare("SELECT TO_CHAR(TO_DATE(dtsaida, 'DD/MM/YY'), 'YYYY-MM-DD') as dataSaida,numcar, dtsaida, dtfecha, dtretorno as dataRetorno, TO_CHAR(TO_DATE(dtretorno, 'DD/MM/YY'), 'YYYY-MM-DD') as dataRetorno  FROM friobom.pccarreg WHERE numcar=:carga");
            $sqlCarga->bindValue(':carga', $numCargas[0]);
            $sqlCarga->execute();
         
            $carreg = $sqlCarga->fetch(PDO::FETCH_ASSOC);
            $dtSaida = $carreg['DATASAIDA'];
            $dtRetorno = $carreg['DATARETORNO'];

            // verificar se ja esta cadastrada
            $sqlConsulta = $db->prepare("SELECT * FROM mdfes WHERE num_mdfe=:mdfe");
            $sqlConsulta->bindValue(':mdfe', $mdfe['NUMMDFE']);
            $sqlConsulta->execute();

            if($sqlConsulta->rowCount()<1){
                $sqlInsert = $db->prepare("INSERT INTO mdfes (num_mdfe, cargas, veiculo, motorista, situacao, data_saida) VALUES(:mdfe, :cargas,:veiculo, :motorista, :situacao, :dataSaida)");
                $sqlInsert->bindValue(':mdfe', $mdfe['NUMMDFE']);
                $sqlInsert->bindValue(':cargas', $cargasText);
                $sqlInsert->bindValue(':veiculo',$mdfe['PLACA']);
                $sqlInsert->bindValue(':motorista', $mdfe['MOTORISTA'] );
                $sqlInsert->bindValue(':situacao', $msg);
                $sqlInsert->bindValue(':dataSaida', $dtSaida);
                $sqlInsert->execute();
            }else{
                $sqlUpdate = $db->prepare("UPDATE mdfes SET situacao=:situacao, data_retorno=:dtRetorno WHERE num_mdfe=:mdfe");
                $sqlUpdate->bindValue(':mdfe', $mdfe['NUMMDFE']);
                $sqlUpdate->bindValue(':situacao', $msg);
                $sqlUpdate->bindValue(':dtRetorno', $dtRetorno);
                $sqlUpdate->execute();
            }

        }

        return true;
    }catch(Exception $e){
        return false;
    }

}

function qtdRegistros($parametro){
    include '../conexao.php';

    $sql = $db->prepare("SELECT COUNT(*)  FROM motoristas_ponto WHERE mdfe=:mdfe");
    $sql->bindValue(':mdfe', $parametro);
    $sql->execute();
      
    $qtd = $sql->fetchColumn();

    return $qtd;
}

if(isset($_POST['acao'])){
    if($_POST['acao']=='qtdRegistros'){
        $mdfe = $_POST['mdfe'];
        $numPontos = qtdRegistros($mdfe);
        echo json_encode($numPontos);
    }
}
