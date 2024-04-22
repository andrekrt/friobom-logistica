<?php 
function registraNf(){
    require_once "../conexao-oracle.php";
    include_once "../conexao.php";

    try{
        $sqlPrepare = "SELECT x.numnota, 
        TO_CHAR(TO_DATE(x.DTEMISSAO, 'DD/MM/YY'), 'YYYY-MM-DD') as dataEmissao,
        TO_CHAR(TO_DATE(e.dtent, 'DD/MM/YY'), 'YYYY-MM-DD') as dataEntrada,
        SUBSTR(DADOSXML, 
            INSTR(DADOSXML, '<xNome>') + LENGTH('<xNome>'), 
            INSTR(DADOSXML, '</xNome>') - INSTR(DADOSXML, '<xNome>') - LENGTH('<xNome>')) AS nomeEmit,
        cnpj,
        SUBSTR(DADOSXML, 
            INSTR(DADOSXML, '<xMun>') + LENGTH('<xMun>'), 
            INSTR(DADOSXML, '</xMun>') - INSTR(DADOSXML, '<xMun>') - LENGTH('<xMun>')) AS cidade,
        SUBSTR(DADOSXML, 
            INSTR(DADOSXML, '<UF>') + LENGTH('<UF>'), 
            INSTR(DADOSXML, '</UF>') - INSTR(DADOSXML, '<UF>') - LENGTH('<UF>')) AS uf,
        SUBSTR(DADOSXML, 
            INSTR(DADOSXML, '<fone>') + LENGTH('<fone>'), 
            INSTR(DADOSXML, '</fone>') - INSTR(DADOSXML, '<fone>') - LENGTH('<fone>')) AS fone,
        SUBSTR(DADOSXML, 
            INSTR(DADOSXML, '<CFOP>') + LENGTH('<CFOP>'), 
            INSTR(DADOSXML, '</CFOP>') - INSTR(DADOSXML, '<CFOP>') - LENGTH('<CFOP>')) AS cfop,
        SUBSTR(DADOSXML, 
            INSTR(DADOSXML, '<refNFe>') + LENGTH('<refNFe>'), 
            INSTR(DADOSXML, '</refNFe>') - INSTR(DADOSXML, '<refNFe>') - LENGTH('<refNFe>')) AS nfRef,
            x.chavenfe,
            m.vltotalnfe,
            DECODE(SITCONFIRMACAODEST,
                0,
                'SEM MANIFESTAÇÃO',
                1,
                'CONFIRMAÇÃO DA OPERAÇÃO',
                2,
                'DESCONHECIMENTO DA OPERAÇÃO',
                3,
                'OPERAÇÃO NÃO REALIZADA',
                4,
                'CIÊNCIA DA OPERAÇÃO') AS SITUACAOMANIF,
                e.codfornec, 
                DADOSXML
        FROM friobom.pcnfentxml x
        LEFT JOIN friobom.PCMANIFDESTINATARIO m ON x.chavenfe=m.chavenfe 
        LEFT JOIN FRIOBOM.pcnfent e ON x.chavenfe=e.chavenfe
        WHERE x.DTEMISSAO >= '01/01/24'";

        $sql = $dbora->prepare($sqlPrepare);
        $sql->execute();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $cfop = stream_get_contents($row['CFOP']);
            if($cfop=='5202' || $cfop=='5411' || $cfop=='6202' || $cfop=='6411') {
                $numNota = $row['NUMNOTA'];
                $dtEmisao =  $row['DATAEMISSAO'];
                $dtEntrada = $row['DATAENTRADA'];
                $emitente = stream_get_contents($row['NOMEEMIT']);
                $cnpjEmit = $row['CNPJ'];
                $cidade = stream_get_contents($row['CIDADE']);
                $uf = stream_get_contents($row['UF']);
                $fone = !empty($row['FONE'])?stream_get_contents($row['FONE']):"";
                $chave = $row['CHAVENFE'];
                $referente = stream_get_contents($row['NFREF']);
                $valorNf = str_replace(",",".",$row['VLTOTALNFE']) ;
                $manifestacao = $row['SITUACAOMANIF'];
                $fornecedor = $row['CODFORNEC'];
                $xml = (stream_get_contents($row['DADOSXML']));
                $situacao = empty($dtEntrada)?"Pendente":"Finalizado";

                // pegar carregamento
                $sqlCarreg = $dbora->prepare('SELECT n.numcar, c.dtfecha FROM FRIOBOM.pcnfsaid n LEFT JOIN FRIOBOM.pccarreg c ON n.numcar= c.numcar WHERE chavenfe=:chave');
                $sqlCarreg->bindValue(':chave', $referente);
                $sqlCarreg->execute();        
                $carreg = $sqlCarreg->fetch(PDO::FETCH_ASSOC);
                $statusCarga = $carreg['DTFECHA']==null?"Em Aberto":"Carga Fechada";

                // pegar os cean
                $xmlElement = new SimpleXMLElement($xml);
                $status = [];
                
                foreach($xmlElement->NFe->infNFe->det as $det){
                    $cean = ($det->prod->cEAN);
                    preg_match_all('/\d+/', $cean, $numbers);
                    $ceanNum = $numbers[0];
                    $qtd = ($det->prod->qCom)?$det->prod->qCom:0;
                    $valor = ($det->prod->vUnCom)?$det->prod->vUnCom:0;
                    $sqlEan = $dbora->prepare("SELECT chavenfe, n.numped, p.codprod, qt,  p.pvenda, codauxiliartrib
                    FROM FRIOBOM.pcnfsaid n 
                    LEFT JOIN friobom.pcpedi p ON p.numped = n.numped 
                    LEFT JOIN friobom.pcprodut pd ON pd.codprod = p.codprod
                    WHERE chavenfe=:chave AND codauxiliartrib=:ean");
                    $sqlEan->bindValue(':chave', $referente);
                    $sqlEan->bindValue(':ean', implode('', $ceanNum));
                    $sqlEan->execute();    
                    $dados = $sqlEan->fetchAll(PDO::FETCH_ASSOC);
                    
                    if(count($dados)>0){
                    //    var_dump($dados);
                        if($valor!=str_replace(",",".", $dados[0]['PVENDA'])){
                            $status[] = "Preço Divergente";
                        }elseif($qtd>str_replace(",",".", $dados[0]['QT'])){
                            $status[] = "Quantidade Superior a Comprada";
                        }else{
                            $status[] = "Tudo OK";
                        }
                    }else{
                        $status[] = "Produto inexistente";
                    }
                   
                }
                
                $statusFinal = pegarStatus($status);

                // verificar se ja esta cadastrada
                $sqlConsulta = $db->prepare("SELECT * FROM nfs_xml WHERE chave_nf=:chaveNf");
                $sqlConsulta->bindValue(':chaveNf', $chave);
                $sqlConsulta->execute();
                if($sqlConsulta->rowCount()<1){
                    $sqlInsert = $db->prepare("INSERT INTO nfs_xml (num_nota, data_emissao, data_entrada, nome_emit, cnpj, cidade, uf, fone, cfop, chave_nf, valor_total, situacao_manifest, chave_referente, fornecedor, carregamento, status_carga, divergencia, situacao) VALUES(:numNota, :dtEmissao, :dtEntrada, :nome, :cnpj, :cidade, :uf, :fone, :cfop, :chaveNf, :vlTotal, :manifestacao, :chaveRef, :fornecedor, :carregamento, :statusCarga, :divergencia,:situacao)");
                    $sqlInsert->bindValue(':numNota', $numNota);
                    $sqlInsert->bindValue(':dtEmissao',$dtEmisao);
                    $sqlInsert->bindValue(':dtEntrada', $dtEntrada );
                    $sqlInsert->bindValue(':nome', $emitente);
                    $sqlInsert->bindValue(':cnpj', $cnpjEmit);
                    $sqlInsert->bindValue(':cidade', $cidade);
                    $sqlInsert->bindValue(':uf', $uf);
                    $sqlInsert->bindValue(':fone', $fone);
                    $sqlInsert->bindValue(':cfop', $cfop);
                    $sqlInsert->bindValue(':chaveNf', $chave);
                    $sqlInsert->bindValue(':vlTotal', $valorNf);
                    $sqlInsert->bindValue(':manifestacao', $manifestacao);
                    $sqlInsert->bindValue(':chaveRef', $referente);
                    $sqlInsert->bindValue(':fornecedor', $fornecedor);
                    $sqlInsert->bindValue(':carregamento', $carreg['NUMCAR']);
                    $sqlInsert->bindValue(':statusCarga', $statusCarga);
                    $sqlInsert->bindValue(':divergencia', $statusFinal);
                    $sqlInsert->bindValue(':situacao', $situacao);
                    $sqlInsert->execute();
                }else{
                    if(!empty($dtEntrada)){
                        $sqlUpdate = $db->prepare("UPDATE nfs_xml SET data_entrada=:dtEntrada, situacao=:situacao WHERE chave_nf=:chaveNf");
                        $sqlUpdate->bindValue(':dtEntrada', $dtEntrada);
                        $sqlUpdate->bindValue(':situacao', $situacao);
                        $sqlUpdate->bindValue(':chaveNf', $chave);
                        $sqlUpdate->execute();
                    }
                    
                    
                }
            }
        
        }

        return true;
    }catch(Exception $e){
        return false;
    }

}

// registraNf();

function pegarStatus($statusArray){
   

    foreach($statusArray as $status){
        if($status !== 'Tudo OK'){
            return $status;
        }
    }

    return 'Tudo OK';
}

function atualizarEntrada(){

}