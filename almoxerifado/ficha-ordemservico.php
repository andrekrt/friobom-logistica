<?php

use Mpdf\Mpdf;
require_once __DIR__ . '/vendor/autoload.php';
require("../conexao.php");

$idOrdemServico = filter_input(INPUT_GET, 'id');

$sql= $db->prepare("SELECT * FROM ordem_servico WHERE idordem_servico = :idOrdemServico ");
$sql->bindValue(':idOrdemServico', $idOrdemServico);

if($sql->execute()){
    $dados = $sql->fetchAll();
    foreach($dados as $dado){

        $dataAbertura = date("d/m/Y", strtotime($dado['data_abertura']));
        $placa = $dado['placa'];
        $corretiva = $dado['corretiva']?"Corretiva":"";
        $preventiva = $dado['preventiva']?"Preventiva":"";
        $externa = $dado['externa']?"Manutenção Externa":"";
        $oleo = $dado['oleo']?"Troca de Óleo":"";
        $higienizacao = $dado['higienizacao']?"Higienização":"";
        $horaAbertura = date("H:i:s", strtotime($dado['data_abertura']));
        $obs = $dado['obs'];

    }
}

$mpdf = new Mpdf();
$mpdf->AddPage('P');

$estilo = "

    .logo{
        width:200px;
    }
    .titulo{
        text-align:center;
        font-size: 15px;
    }
    .subtitulo{
        font-size:10px;
        text-align:center;
    }
    
";

// $mpdf->writeHTML($estilo,1);
// $mpdf->WriteHTML("
// <!DOCTYPE html>
// <html lang='pt-br'>
//     <body>
//         <table border='1' style='width:668px'>
//             <tr>
//                 <td colspan='2'><img class='logo' src='../assets/images/logo.png'></td>
//                 <td colspan='4'> <h2 class='titulo'>FICHA DE ACOMPANHAMENTO DE SERVIÇO REALIZADO NO VEÍCULO OFICINA</h2> </td>
//             </tr>
//             <tr>
//                 <td colspan='2'> <span style='font-weight:bold'>DATA:</span>  $dataAbertura</td>
//                 <td colspan='2'><span style='font-weight:bold'>PLACA:</span> $placa</td>
//                 <td colspan='2'><span style='font-weight:bold'>O.S:</span> $idOrdemServico</td>
//             </tr>
//         </table>
//         <img  src='../assets/images/cab-ordem.png'>
//         <table border='1' style='width:668px'>
//             <tr>
//                 <td colspan='3' style='font-weight:bold' >Hora Abertura da Ordem de Serviço</td>
//                 <td>$horaAbertura</td>
//             </tr>
//             <tr>
//                 <td style='font-weight:bold'>Observações</td>
//                 <td colspan='3'>$obs</td>
//             </tr>
//             <tr>
//                 <td  style='font-weight:bold'> Tipo de Revisão </td>
//                 <td colspan='3'>$corretiva $preventiva $externa $oleo $higienizacao</td>
//             </tr>
//         </table>
//         <img  src='../assets/images/rodape-ordem.png'>
//     </body>
// </html>
// ");

//$mpdf->Output();
$consulta = $db->prepare("SELECT servicos_almoxarifado.descricao as servico,  servicos_almoxarifado.categoria as categoria, peca_reparo.descricao as peca, qtd FROM ordem_servico LEFT JOIN saida_estoque ON ordem_servico.idordem_servico = saida_estoque.os LEFT JOIN servicos_almoxarifado ON saida_estoque.servico = servicos_almoxarifado.idservicos LEFT JOIN peca_reparo ON saida_estoque.peca_idpeca = peca_reparo.id_peca_reparo WHERE idordem_servico = :idOrdemServico");
$consulta->bindValue(':idOrdemServico', $idOrdemServico);
$consulta->execute();
$pecas = $consulta->fetchAll();

$html = "
<table border='1' style='width:668px'>
    <tr>
        <td colspan='2'><img class='logo' src='../assets/images/logo.png'></td>
        <td colspan='4'> <h2 class='titulo'>FICHA DE ACOMPANHAMENTO DE SERVIÇO REALIZADO NO VEÍCULO OFICINA</h2> </td>
    </tr>
    <tr>
        <td colspan='2'> <span style='font-weight:bold'>DATA:</span>  $dataAbertura</td>
        <td colspan='2'><span style='font-weight:bold'>PLACA:</span> $placa</td>
        <td colspan='2'><span style='font-weight:bold'>O.S:</span> $idOrdemServico</td>
    </tr>
</table>
<table border='1' style='width:668px;'>
    <tr>
        <td colspan='3' style='font-weight:bold'>Hora Abertura da Ordem de Serviço</td>
        <td>$horaAbertura</td>
    </tr>
        <tr>
        <td style='font-weight:bold'>Observações</td>
            <td colspan='3'>$obs</td>
    </tr>
    <tr>
        <td  style='font-weight:bold'> Tipo de Revisão </td>
        <td colspan='3'>$corretiva $preventiva $externa $oleo $higienizacao</td>
    </tr>
</table>
<table border='1' style='width:668px; margin-top:10px' >
    <tr>
        <th>Serviço</th>
        <th>Categoria Serviço</th>
        <th>Peça</th>
        <th>Qtd</th>
    </tr>";
    foreach($pecas as $peca):
    $html .=  "<tr>";
    $html .=  "<td>$peca[servico] </td>";
    $html .=  "<td>$peca[categoria] </td>";
    $html .=  "<td>$peca[peca] </td>";
    $html .=  "<td>$peca[qtd] </td>";
    $html .=  "</tr>";
    endforeach;
    $html.="</table>";

$mpdf->writeHTML($estilo,1);
$mpdf->WriteHTML($html);
$mpdf->Output();

?>