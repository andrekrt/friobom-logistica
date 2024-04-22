<?php 
include('../conexao.php');
require "../conexao-oracle.php";
$id = $_POST['id'];
$sql = $db->query("SELECT chave_nf FROM nfs_xml WHERE idnf='$id' LIMIT 1");
$row = $sql->fetch(PDO::FETCH_ASSOC);
$chaveNf = $row['chave_nf'];
$sqlXml = $dbora->prepare("SELECT DADOSXML FROM FRIOBOM.pcnfentxml WHERE chavenfe=:chave");
$sqlXml->bindValue(':chave', $chaveNf);
$sqlXml->execute();
$xmlResource = $sqlXml->fetchColumn();
$xmlContent=stream_get_contents($xmlResource);
$xmlFormat = simplexml_load_string($xmlContent);
$chaveRef = (string) $xmlFormat->NFe->infNFe->ide->NFref->refNFe;

// pegar produtos da nf referencia
$sqlRef = $dbora->prepare("SELECT nf.numped, pe.codprod, pd.descricao, pe.qt, pd.unidade, pe.pvenda,pd.codauxiliartrib FROM friobom.pcnfsaid nf 
LEFT JOIN friobom.pcpedi pe ON nf.numped = pe.numped
LEFT JOIN friobom.pcprodut pd ON pe.codprod=pd.codprod
WHERE chavenfe=:chaveRef");
$sqlRef->bindValue(':chaveRef', $chaveRef);
$sqlRef->execute();
$produtosRef = $sqlRef->fetchAll(PDO::FETCH_ASSOC);

$dadosXml=[
    'natOp'=>(string) $xmlFormat->NFe->infNFe->ide->natOp,
    'tipoOp'=>(string) $xmlFormat->NFe->infNFe->ide->tpNF,
    'chave'=>(string) $xmlFormat->protNFe->infProt->chNFe,
    'modelo'=>(string) $xmlFormat->NFe->infNFe->ide->mod,
    'serie'=>(string) $xmlFormat->NFe->infNFe->ide->serie,
    'numNota'=> (string) $xmlFormat->NFe->infNFe->ide->nNF,
    'dtEmissao'=> (string) $xmlFormat->NFe->infNFe->ide->dhEmi,
    'cnpjEmit'=> (string) $xmlFormat->NFe->infNFe->emit->CNPJ,
    'ieEmit' => (string) $xmlFormat->NFe->infNFe->emit->IE,
    'razaoEmit' => (string) $xmlFormat->NFe->infNFe->emit->xNome,
    'cityEmit'=> (string) $xmlFormat->NFe->infNFe->emit->enderEmit->xMun,
    'ufEmit'=> (string) $xmlFormat->NFe->infNFe->emit->enderEmit->UF,
    'produtos' => [],
    'valorNf' => number_format((float) $xmlFormat->NFe->infNFe->total->ICMSTot->vNF,2,",","."),
    'produtosRef' => [],
    'chaveRef' => $chaveRef
];

foreach($xmlFormat->NFe->infNFe->det as $det){
    
    $codBarra = (string) $det->prod->cEAN;
    $valorUnit = number_format((float) $det->prod->vUnCom, 2, ",",".");
    $qtd = number_format((float) $det->prod->vUnCom, 2, ",",".");
    $cor = 'black';
    foreach($produtosRef as $produtoRef){
        if($codBarra==$produtoRef['CODAUXILIARTRIB'] ){
            $qtdRef = str_replace(",",".",$produtoRef['QT']);
            $precoRef = str_replace(",",".",$produtoRef['PVENDA']);
            if( $qtdRef<(float)$det->prod->qCom){
                $cor = 'red';
                break;
            }

            if($precoRef!=(float)$det->prod->vUnCom){
                $cor = 'red';
                break;
            }
            $cor = 'black';
            break;
        }else{
            $cor = 'red';
        }
    }
    // $cor = in_array($codBarra, array_column($produtosRef,'CODAUXILIARTRIB'))?"black":"red";
    $produtos =[
        'descricao'=>(string) $det->prod->xProd. " (".$codBarra. ")",
        'qtd'=> number_format((float)  $det->prod->qCom, 2, ",","."),
        'und'=> (string) $det->prod->uCom,
        'vl_unit'=> number_format((float) $det->prod->vUnCom, 2, ",",".") ,
        'vlTotal'=> number_format((float) $det->prod->vProd,2,",","."),
        'cor' => $cor
    ];

    $dadosXml['produtos'][]=$produtos;
}

foreach($produtosRef as $produtoRef){
    $listProdRef = [
        'descricaoRef' => $produtoRef['DESCRICAO'] ."(". $produtoRef['CODAUXILIARTRIB'] .")",
        'qtdRef' => $produtoRef['QT'],
        'undRef' => $produtoRef['UNIDADE'],
        'vl_unitRef' => $produtoRef['PVENDA'],
        'vlTotalRef' =>number_format((str_replace(",",".", $produtoRef['QT'])*str_replace(",",".", $produtoRef['PVENDA'])),2,",","." ) 
    ];

    $dadosXml['produtosRef'][]= $listProdRef;
}

echo json_encode($dadosXml);
?>
