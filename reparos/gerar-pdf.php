<?php 
use Mpdf\Mpdf;
require_once __DIR__ . '/vendor/autoload.php';
require("../conexao.php");

$data = date("d/m/y");
$idReparo = filter_input(INPUT_GET,"id");

$sql = $db->query("SELECT * FROM solicitacoes WHERE id = $idReparo");
if($sql){
    $dados=$sql->fetchAll();
    foreach($dados as $dado){
        $dataSolic = date("d/m/Y", strtotime($dado['dataAtual']));
        $dataAprovacao = date("d/m/Y", strtotime($dado['dataAprov']));
        $servico = $dado['servico'];
        $descricao = $dado['descricao'];
        $veiculo = $dado['placarVeiculo'];
        $situacao = $dado['statusSolic'];
        $obs = $dado['obs'];
        $valor = $dado['valor'];
        $local = $dado['localReparo'];
        $numSolic = $dado['id'];
    }
}
$select = $db->query("SELECT * FROM solicitacoes02 WHERE idSocPrinc = $idReparo ");
if($select->rowCount()>0){
    $dados = $select->fetch();
    $valorFinal = str_replace(",",".",$dados['valor']);
    $valorInicial = str_replace(",",".", $valor);

    $diferenca = str_replace(".",",",$valorFinal+$valorInicial) ;
    $msg = "
        <table border='1' style='width:100%'>
            <thead>
                <tr>
                    <td> <span style='font-weight:bold'> Nova Peça/Serviço: </span>  $dados[servico]  </td>
                    <td> <span style='font-weight:bold'> Nova Peça/Serviço: </span>  $dados[descricao] </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> <span style='font-weight:bold'> Valor Adicional: </span> R$ $dados[valor] </td>
                    <td> <span style='font-weight:bold'> Valor Total: </span> R$ $diferenca </td>
                </tr>
            </tbody>
        </table><br><br>
    ";
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
                    <td> <span style='font-weight:bold'> Solicitação Nº: </span>  $numSolic</td>
                    <td> <span style='font-weight:bold'> Data da Solicitação: </span>  $dataSolic</td>
                    <td> <span style='font-weight:bold'> Data da Aprovação: </span>  $dataAprovacao</td>
                    <td  style='text-align:center'> <span style='font-weight:bold'> Veículo: </span> $veiculo </td>
                </tr>
                <tr>
                    <td> <span style='font-weight:bold'> Peça(Serviço): </span>  $servico</td>
                    <td> <span style='font-weight:bold'> Descrição: </span> $descricao </td>
                    <td colspan='2'> <span style='font-weight:bold'> Valor(Custo): </span> R$ $valor </td>
                </tr>
                <tr>
                    <td> <span style='font-weight:bold'> Status OS: </span>  $situacao</td>
                    <td> <span style='font-weight:bold'> Observações: </span> $obs </td>
                    <td colspan='2'> <span style='font-weight:bold'> Local do Reparo </span>  $local </td>
                </tr>
            </table><br>
            $msg
            
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
