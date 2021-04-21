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
        $tipoRevisao = $dado['tipo_manutencao'];
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

$mpdf->writeHTML($estilo,1);
$mpdf->WriteHTML("
<!DOCTYPE html>
<html lang='pt-br'>
    <body>
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
        <img  src='../assets/images/cab-ordem.png'>
        <table border='1' style='width:668px'>
            <tr>
                <td style='font-weight:bold' >Hora Abertura da Ordem de Serviço</td>
                <td>$horaAbertura</td>
                <td style='font-weight:bold'>Tipo de Revisão</td>
                <td>$tipoRevisao</td>
            </tr>
            <tr>
                <td style='font-weight:bold'>Observações</td>
                <td colspan='3'>$obs</td>
            </tr>
        </table>
        <img  src='../assets/images/rodape-ordem.png'>
    </body>
</html>
");

$mpdf->Output();

?>
<tr></tr>
<tr >
    <td class='subtitulo'>FUNÇÃO</td>
    <td class='subtitulo'>NOME</td>
    <td colspan='2' class='subtitulo'>INÍCIO</td>
    <td></td>
    <td colspan='2' class='subtitulo'>TÉRMINO</td>
</tr>
<tr>
    <td>LAVADOR</td>
    <td> ______________________________ </td>
    <td>Hora Ent.:</td>
    <td></td>
</tr>