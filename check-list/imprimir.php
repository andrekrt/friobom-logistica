<?php 

use Mpdf\Mpdf;
require_once __DIR__ . '/vendor/autoload.php';
require("../conexao.php");

$idCheck = filter_input(INPUT_GET, 'id');

$sql= $db->prepare("SELECT * FROM check_list WHERE idcheck_list =:idCheck ");
$sql->bindValue(':idCheck', $idCheck);

if($sql->execute()){
    $dados = $sql->fetchAll();
    foreach($dados as $dado){
        $dataCheck = date("d/m/Y", strtotime($dado['data_check'])) ;
        $placa = $dado['placa_veiculo'];
        $tipoVeiculo = $dado['tipo_veiculo'];
        $kmInicial = $dado['km_inicial'];
        $limpeza = $dado['limpeza'];
        $retrovisores = $dado['retrovisores'];
        $paraBrisa = $dado['para_brisa'];
        $quebraSol = $dado['quebra_sol'];
        $pcBordo = $dado['pc_bordo'];
        $buzina = $dado['buzina'];
        $cinto = $dado['cinto'];
        $extintor = $dado['extintor'];
        $triangulo = $dado['triangulo'];
        $macacoChave = $dado['macaco_chave'];
        $taqueCombustivel = $dado['tanque_combustivel'];
        $janelas = $dado['janelas'];
        $setas = $dado['setas'];
        $luzFreio = $dado['luz_freio'];
        $luzRe = $dado['luz_re'];
        $piscaAlerta = $dado['pisca_alerta'];
        $luzesTeto = $dado['luzes_teto'];
        $faixasRefletivas = $dado['faixas_refletivas'];
        $farolDianteiro = $dado['farol_dianteiro'];
        $farolTraseiro = $dado['farol_traseiro'];
        $farolNeblina = $dado['farol_neblina'];
        $farolAlto = $dado['farol_alto'];
        $luzesPainel = $dado['luzes_painel'];
        $pneus = $dado['pneus'];
        $rodas = $dado['rodas'];
        $pneuStepe = $dado['pneu_estepe'];
        $molas = $dado['molas'];
        $cabo_forca = $dado['cabo_forca'];
        $qtdNf = $dado['qtde_nf'];
        $retrovisores = $dado['retrovisores'];
        $valorCarga = str_replace(".", ",",$dado['valor_carga']) ;
        $dataSaida = date("d/m/Y H:i", strtotime($dado['data_saida'])) ;
        $horimetro = $dado['horimetro'];
        $rota = $dado['rota'];
        $pesoCarga = $dado['peso_carga'];
        $numCarregamento = $dado['num_carregemento'];
        $motorista = $dado['motorista'];
        $observacoes = $dado['observacoes'];    
        $situacao = $dado['situacao'];  

    }
}

$mpdf = new Mpdf();
$mpdf->AddPage('L');
$mpdf->WriteHTML("

<body>
    <img src='../assets/images/logo.png' alt='' style='height:80px; margin-top:-50px'>
    <table border='1' style='width:100%; margin-top:5px'>
        <thead>
            <tr>
                <th style='font-size:10pt'>Data Check-List</th>
                <th style='font-size:10pt'>Veículo</th>
                <th style='font-size:10pt'>Placa do Veículo</th>
                <th style='font-size:10pt'>Km Inicial</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style='text-align:center'>$dataCheck</td>
                <td style='text-align:center'> $tipoVeiculo</td>
                <td style='text-align:center'>$placa</td>
                <td style='text-align:center'>$kmInicial</td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th style='font-size:10pt'>Quant. NF's</th>
                <th style='font-size:10pt'>Valor da Carga</th>
                <th style='font-size:10pt'>Data e Hora de Saída</th>
                <th style='font-size:10pt'>Horímetro</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style='text-align:center;font-size:10pt '>$qtdNf</td>
                <td style='text-align:center;font-size:10pt'>R$ $valorCarga</td>
                <td style='text-align:center;font-size:10pt'>$dataSaida</td>
                <td style='text-align:center;font-size:10pt'>$horimetro</td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th style='font-size:10pt;'>Rota</th>
                <th style='font-size:10pt'>Peso Carga</th>
                <th style='font-size:10pt'>N° Carregamento</th>
                <th style='font-size:10pt'>Motorista</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style='text-align:center; font-size:10pt'>$rota</td>
                <td style='text-align:center; font-size:10pt'>R$ $pesoCarga</td>
                <td style='text-align:center; font-size:10pt'>$numCarregamento</td>
                <td style='text-align:center; font-size:10pt'>$motorista</td>
            </tr>
        </tbody>
    </table>
    <table border='1' style='margin-top:10px;width:100%'>
        <thead>
            <tr>
                <th colspan='6'>EQUIPAMENTOS OBIRGATÓRIOS E PRÓPRIOS</th>
            </tr>
        </thead>
        <thead>
            <tr>
                <th style='font-size:10pt'>Limpeza</th>
                <th style='font-size:10pt'>Retrovisores</th>
                <th style='font-size:10pt'>Para Brisas</th>
                <th style='font-size:10pt'>Quebra Sol</th>
                <th style='font-size:10pt'>Computador de Bordo</th>
                <th style='font-size:10pt'>Buzina</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style='text-align:center; font-size:10pt'>$limpeza</td>
                <td style='text-align:center; font-size:10pt'>$retrovisores</td>
                <td style='text-align:center; font-size:10pt'>$paraBrisa</td>
                <td style='text-align:center; font-size:10pt'>$quebraSol</td>
                <td style='text-align:center; font-size:10pt'>$pcBordo</td>
                <td style='text-align:center; font-size:10pt'>$buzina</td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th style='font-size:10pt'>Cinto de Segurança</th>
                <th style='font-size:10pt'>Extintor de Incêndio</th>
                <th style='font-size:10pt'>Triângulo de Sinalização</th>
                <th style='font-size:10pt'>Macaco e Chave de Roda</th>
                <th style='font-size:10pt'>Tanque de Combustível</th>
                <th style='font-size:10pt'>Vidros e Janelas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style='text-align:center; font-size:10pt'>$cinto</td>
                <td style='text-align:center; font-size:10pt'>$extintor</td>
                <td style='text-align:center; font-size:10pt'>$triangulo</td>
                <td style='text-align:center; font-size:10pt'>$macacoChave</td>
                <td style='text-align:center; font-size:10pt'>$taqueCombustivel</td>
                <td style='text-align:center; font-size:10pt'>$janelas</td>
            </tr>
        </tbody>
    </table>
    <table border='1' style='width:100%; margin-top:10px'>
        <thead>
            <tr>
                <th colspan='6'>SISTEMAS DE SINALIZAÇÃO</th>
            </tr>
        </thead>
        <thead>
            <tr>
                <th style='font-size:10pt'>Lanternas Inidicadoras de Direção</th>
                <th style='font-size:10pt'>Lanternas de Freio e Elevada </th>
                <th style='font-size:10pt'>Lanterna de Macha Ré</th>
                <th style='font-size:10pt'>Pisca Alerta</th>
                <th style='font-size:10pt'>Luzes de Sinalização do Teto</th>
                <th style='font-size:10pt'>Faixas Refletivas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style='text-align:center; font-size:10pt'>$setas</td>
                <td style='text-align:center; font-size:10pt'>$luzFreio</td>
                <td style='text-align:center; font-size:10pt'>$luzRe</td>
                <td style='text-align:center; font-size:10pt'>$piscaAlerta</td>
                <td style='text-align:center; font-size:10pt'>$luzesTeto</td>
                <td style='text-align:center; font-size:10pt'>$faixasRefletivas</td>
            </tr>
        </tbody>
    </table>
    <table border='1' style='width:100%; margin-top:10px'>
        <thead>
            <tr>
                <th colspan='5'>SISTEMAS DE ILUMINAÇÃO</th>
            </tr>
        </thead>
        <thead>
            <tr>
                <th style='font-size:10pt'>Farol Dianteiro</th>
                <th style='font-size:10pt'>Farol Traseiro </th>
                <th style='font-size:10pt'>Faróis de Neblina</th>
                <th style='font-size:10pt'>Faróis Alto</th>
                <th style='font-size:10pt'>Luzes do Painel</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style='text-align:center; font-size:10pt'>$farolDianteiro</td>
                <td style='text-align:center; font-size:10pt'>$farolTraseiroar</td>
                <td style='text-align:center; font-size:10pt'>$farolNeblina</td>
                <td style='text-align:center; font-size:10pt'>$farolAlto</td>
                <td style='text-align:center; font-size:10pt'>$luzesPainel</td>
            </tr>
        </tbody>
    </table>
    <table border='1' style='width:100%; margin-top:10px'>
        <thead>
            <tr>
                <th colspan='5'>PNEU E SISTEMA DE SUSPENSÃO</th>
            </tr>
        </thead>
        <thead>
            <tr>
                <th style='font-size:10pt'>Estado Geral dos Pneus</th>
                <th style='font-size:10pt'>Estado Geral da Rodas </th>
                <th style='font-size:10pt'>Pneus de Estepe</th>
                <th style='font-size:10pt'>Estado Geral das Molas</th>
                <th style='font-size:10pt'>Cabo de Força</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style='text-align:center; font-size:10pt'>$pneus</td>
                <td style='text-align:center; font-size:10pt'>$rodas</td>
                <td style='text-align:center; font-size:10pt'>$pneuStepe</td>
                <td style='text-align:center; font-size:10pt'>$molas</td>
                <td style='text-align:center; font-size:10pt'>$cabo_forca</td>
            </tr>
        </tbody>
    </table>
    <table border='1' style='width:100%; margin-top:10px'>
        <thead>
            <tr>
                <th> OBSERVAÇÕES</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>$observacoes</td>
            </tr>
        </tbody>
    </table>
</body>

");
$mpdf->Output();
?>

<body>
    <img src='../assets/images/logo-ver.png' alt='' style='height:80px'>
    <table border='1'>
        <thead>
            <tr>
                <th>Quant. NF's</th>
                <th>Valor da Carga</th>
                <th>Veículo</th>
                <th>Placa de Veículo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>$qtdNf</td>
                <td>$valorCarga</td>
                <td>$tipoVeiculo</td>
                <td>$placa</td>
            </tr>
        </tbody>
    </table>
</body>