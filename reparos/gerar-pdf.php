<?php 
use Mpdf\Mpdf;
require_once __DIR__ . '/vendor/autoload.php';
require("../conexao.php");

$data = date("d/m/y");
$token = filter_input(INPUT_GET,"token");

$sql = $db->prepare("SELECT id, token, data_atual, placa, problema, local_reparo, imagem as anexo, GROUP_CONCAT('- ', descricao) as peca, GROUP_CONCAT(qtd) as qtd, GROUP_CONCAT('R$ ', vl_unit) as vlUnit, GROUP_CONCAT('R$ ', vl_total) as vlTotal, solicitacoes_new.situacao, usuario, SUM(vl_total) as vlFinal, data_aprovacao, obs FROM `solicitacoes_new` LEFT JOIN peca_reparo ON solicitacoes_new.peca_servico = peca_reparo.id_peca_reparo WHERE token = :token GROUP BY problema, placa");
$sql->bindValue(':token',$token);
$sql->execute();

$dados=$sql->fetchAll();
foreach($dados as $dado){
    $dataSolic = date("d/m/Y", strtotime($dado['data_atual']));
    $dataAprovacao = date("d/m/Y", strtotime($dado['data_aprovacao']));
    $descricao = $dado['problema'];
    $veiculo = $dado['placa'];
    $situacao = $dado['situacao'];
    $obs = $dado['obs'];
    $local = $dado['local_reparo'];
    $peca = str_replace(",","<br>", $dado['peca']);
    $qtd = str_replace(".",",",str_replace(",","<br>",$dado['qtd']))  ;
    $vlUnit = str_replace(".",",",str_replace(",","<br>",$dado['vlUnit']))  ;
    $vlTotal = str_replace(".",",", str_replace(",","<br>", $dado['vlTotal'])) ;
    $vlFinal = str_replace(".",",",$dado['vlFinal']) ;
 ?>

<?php
}


$mpdf = new Mpdf();
$mpdf->WriteHTML("
<!DOCTYPE html>
<html lang='pt-br'>

    <body>
        
    
            <img src='../assets/images/logo.png' style='float:left; width:25%'>
            <h1 style='text-align:right; font-size:17px'>BASTO MESQUITA DISTRIBUIÇÃO E LOGISTICA LTDA</h1>
            <p style='text-align:left; font-size:17px'>
                CNPJ: 12.464.051/0001-53 <br>
                BR 316, S/N - RUI BARBOSA - BACABAL - MA <br>
                E-mail: mesquitafriobom@gmail.com - Fone: (99)99152-0509 <br>
                Emissão:  $data
                
            </p>
            <table border='1' style='width:100%'>
                <thead>
                    <tr>
                        <th style='text-align:left'>Solicitante</th>
                        <th style='text-align:right'>Administrativo </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Setor: Transporte</td>
                        <td style='text-align:right'>Setor: Recursos Humanos</td>
                    </tr>
                    <tr>
                        <td>Responsável: Gilvan</td>
                        <td style='text-align:right'>Responsável: Francisca Nascimento Silva</td>
                    </tr>
                     <tr>
                        <td>E-mail: transporte@friobombacabal.com.br</td>
                        <td style='text-align:right'>E-mail: rhfriobom@gmail.com</td>
                    </tr>
                    <tr>
                        <td>Contato: (99) 98234-6118</td>
                        <td style='text-align:right'>Contato: (99) 99146-0621</td>
                    </tr>
                </tbody>
            </table><br>
            <table style='width:100%' border='1'>
                <tr>
                    <td> <span style='font-weight:bold'> Solicitação Nº: </span>  $token</td>
                    <td> <span style='font-weight:bold'> Data da Solicitação: </span>  $dataSolic</td>
                    <td> <span style='font-weight:bold'> Data da Aprovação: </span>  $dataAprovacao</td>
                    <td  style='text-align:center'> <span style='font-weight:bold'> Veículo: </span> $veiculo </td>
                </tr>
                <tr>
                    <td> <span style='font-weight:bold'> Peças/Serviços: </span>  </td>
                    <td> <span style='font-weight:bold'> Qtd: </span>  </td>
                    <td > <span style='font-weight:bold'> Valor(Unit.): </span>  </td>
                    <td > <span style='font-weight:bold'> Valor Total: </span>  </td>
                </tr>
                <tr>
                    <td> $peca </td>
                    <td> $qtd </td>
                    <td>$vlUnit</td>
                    <td>$vlTotal</td>
                </tr>
                <tr>
                    <td> <span style='font-weight:bold'> Problema: </span>  $descricao</td>
                    <td > <span style='font-weight:bold'> Local do Reparo: </span>  $local </td>
                    <td > <span style='font-weight:bold'> Obs.: </span>  $obs </td>
                    <td > <span style='font-weight:bold'> Valor Final </span> R$ $vlFinal </td>
                </tr>
            </table><br>
            
            <div style='width:100%; text-align:center'>
                <img src='../assets/images/assinatura.png' style='height:100px; margin-bottom:-65px'>
                <div>____________________________</div>
                <span>Francisca Nascimento Silva</span><br>
                <span style='font-weight:bold'>Departamento RH</span>
            </div>
            
    </body>
</html>
");



$mpdf->Output();

?>
