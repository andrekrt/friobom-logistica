<?php 
use Mpdf\Mpdf;
require_once __DIR__ . '/../vendor/autoload.php';
require("../conexao.php");


$id = filter_input(INPUT_GET,"id");

$sql = $db->prepare("SELECT * FROM checklist LEFT JOIN veiculos ON checklist.veiculo = veiculos.cod_interno_veiculo LEFT JOIN rotas ON checklist.rota = rotas.cod_rota LEFT JOIN motoristas ON checklist.motorista = motoristas.cod_interno_motorista LEFT JOIN usuarios ON checklist.usuario = usuarios.idusuarios WHERE idchecklist = :id");
$sql->bindValue(':id',$id);

if($sql->execute()){
    $dados=$sql->fetchAll();

    foreach($dados as $dado){
        $qtdNF = $dado['qtdnf'];
        $vlCarga = number_format($dado['vl_carga'],2,",",".") ;
        $tipoVeiculo = $dado['tipo_veiculo'];
        $placa = $dado['placa_veiculo'];
        $dataSaida = date("d/m/Y", strtotime($dado['saida']));
        $kmInicial = $dado['km_saida'];
        $hrSaida = $dado['hora_saida'];
        $previsaoChegada = date("d/m/Y", strtotime($dado['previsao_chegada'])) ;
        $horimetro = $dado['horimetro'];
        $rota = $dado['nome_rota'];
        $pesoCarga =number_format($dado['peso_carga'],"2",",",".");
        $carregamento = $dado['carregamento'];
        $motorista = $dado['nome_motorista'];
        $ajudante = $dado['ajudante'];
        $cabine = $dado['cabine'];
        $retrovisores = $dado['retrovisores'];
        $parabrisa = $dado['parabrisas'];
        $quebasol = $dado['quebra_sol'];
        $bordo = $dado['bordo'];
        $buzina = $dado['buzina'];
        $cinto = $dado['cinto'];
        $extintor = $dado['extintor'];
        $triangulo =$dado['triangulo'];
        $macaco = $dado['macaco'];
        $tanque = $dado['tanque'];
        $janela = $dado['janelas'];
        $banco = $dado['banco'];
        $porta = $dado['porta'];
        $cambio = $dado['cambio'];
        $seta = $dado['seta'];
        $luzFreio = $dado['luz_freio'];
        $luzRe = $dado['luz_re'];
        $alerta = $dado['alerta'];
        $luzTeto =$dado['luz_teto'];
        $faixas = $dado['faixas'];
        $pneus = $dado['pneus'];
        $rodas = $dado['rodas'];
        $estepe = $dado['estepe'];
        $molas = $dado['molas'];
        $cabo = $dado['cabo_forca'];
        $refrigeracao = $dado['refrigeracao'];
        $ventilador = $dado['ventiladores'];
        $farolDianteiro = $dado['farol_dianteiro'];
        $farolTraseiro = $dado['farol_traseiro'];
        $farolNeblina = $dado['farol_neblina'];
        $farolAlto = $dado['farol_alto'];
        $luzPainel = $dado['painel'];
        $dataChegada = date("d/m/Y", strtotime($dado['chegada'])) ;
        $kmRota = $dado['km_rota'];
        $ltAbastecido = $dado['litros_abastecido'];
        $vlAbastecido =number_format($dado['valor_abastecido'],2,",",".") ;
        $mediaConsumo = number_format($dado['media_consumo'],2,",",".") ;
        $obs = nl2br($dado['obs']);
        
    }
}else{
    print_r($sql->errorInfo());
}

$mpdf = new Mpdf();
$mpdf->WriteHTML("
<!DOCTYPE html>
<html lang='pt-br'>
    <body style='width:100%'>
        <img src='../assets/images/logo.png' style=' width:50%; margin-top:-50px; margin-left:auto; margin-right:auto '>
        <table border='' style='width:100%; margin-top:20px'>
            <tr>
                <td>Qtd NF's: $qtdNF</td>
                <td>Valor da Carga(R$): $vlCarga</td>
                <td>Veículo: $tipoVeiculo</td>
            </tr>
            <tr>
                <td>Placa: $placa</td>
                <td>Data de Saída: $dataSaida</td>
                <td>Km Inicial: $kmInicial</td>
            </tr>
            <tr>
                <td>Hora Saída: $hrSaida</td>
                <td>Previsão de Chegada: $previsaoChegada</td>
                <td>Horímetro: $horimetro</td>
            </tr>
            <tr>
                <td>Rota: $rota</td>
                <td>Peso da Carga: $pesoCarga</td>
                <td>Nº Carregamento: $carregamento</td>
            </tr>
            <tr>
                <td colspan='2'>Motorista: $motorista</td>
                <td>Ajudante: $ajudante</td>
            </tr>
        </table>

        <table border='1' style='width:100%;font-size:9pt'>
            <thead>
                <tr>
                    <th colspan='2' style='text-align:center'>EQUIPAMENTOS OBRIGATÓRIO E PRÓPRIOS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Limpeza da Cabine</td>
                    <td style='text-align:right'>$cabine</td>
                </tr>
                <tr>
                    <td>Retrovisores</td>
                    <td style='text-align:right'>$retrovisores</td>
                </tr>
                    <tr>
                    <td>Limpador e Lavador de Para-Brisa</td>
                    <td style='text-align:right'>$parabrisa</td>
                </tr>
                <tr>
                    <td>Quebra Sol</td>
                    <td style='text-align:right'>$quebasol</td>
                </tr>
                <tr>
                    <td>Velocímetro/Tacog./Comp. de Bordo</td>
                    <td style='text-align:right'>$bordo</td>
                </tr>
                <tr>
                    <td>Buzina</td>
                    <td style='text-align:right'>$buzina</td>
                </tr>
                <tr>
                    <td>Cinto de Segurança</td>
                    <td style='text-align:right'>$cinto</td>
                </tr>
                <tr>
                    <td>Extintor de Incêndio</td>
                    <td style='text-align:right'>$extintor</td>
                </tr>
                <tr>
                    <td>Triângulo de Sinalização</td>
                    <td style='text-align:right'>$triangulo</td>
                </tr>
                <tr>
                    <td>Macaco e Chave de Roda</td>
                    <td style='text-align:right'>$macaco</td>
                </tr>
                <tr>
                    <td>Portas e Tampas do Tanque de Comb.</td>
                    <td style='text-align:right'>$tanque</td>
                </tr>
                <tr>
                    <td>Vidros e Janelas</td>
                    <td style='text-align:right'>$janela</td>
                </tr>
                <tr>
                    <td>Forro do Banco</td>
                    <td style='text-align:right'>$banco</td>
                </tr>
                <tr>
                    <td>Maçaneta da Porta</td>
                    <td style='text-align:right'>$porta</td>
                </tr>
                <tr>
                    <td>Alavanca do Câmbio</td>
                    <td style='text-align:right'>$cambio</td>
                </tr>
            </tbody>
        </table>
        <table border='1' style='width:100%;font-size:9pt'>
        <tr>
            <th colspan='2'>SISTEMA DE SINALIZAÇÃO</th>
            <th colspan='2'>SISTEMA DE ILUMINAÇÃO</th>
        </tr>
        <tr>
            <td>Lanternas Indicadoras de Direção</td>
            <td style='text-align:right'>$seta</td>
            <td>Farol Dianteiro</td>
            <td style='text-align:right'>$farolDianteiro</td>
        </tr>
        <tr>
            <td>Lanternas de Freio e Elevada</td>
            <td style='text-align:right'>$luzFreio</td>
            <td>Farol Traseiro</td>
            <td style='text-align:right'>$farolTraseiro</td>
        </tr>
        <tr>
            <td>Lanterna de Marcha Ré</td>
            <td style='text-align:right'>$luzRe</td>
            <td>Farol de Neblina</td>
            <td style='text-align:right'>$farolNeblina</td>
        </tr>
        <tr>
            <td>Pisca Alerta</td>
            <td style='text-align:right'>$alerta</td>
            <td>Farol de Longo Alcance(Alto)</td>
            <td style='text-align:right'>$farolAlto</td>
        </tr>
        <tr>
            <td>Luzes de Sinalização Intermitente do Teto</td>
            <td style='text-align:right'>$luzTeto</td>
            <td>Luzes do Painel</td>
            <td style='text-align:right'>$luzPainel</td>
        </tr>
        <tr>
            <td>Faixas Refletivas/Retrorefletivas</td>
            <td style='text-align:right'>$faixas</td>
        </tr>
        
        <tr>
            <th colspan='2'>PNEUS E SISTEMA DE SUSPENSÃO</th>
            <th colspan='2'>EQUIPAMENTOS DE REFRIGERAÇÃO</th>
        </tr>
        <tr>
            <td>Estado Geral dos Pneus</td>
            <td style='text-align:right'>$pneus</td>
            <td>Cabo de Força</td>
            <td style='text-align:right'>$cabo</td>
        </tr>
        <tr>
            <td>Estado Geral da Rodas</td>
            <td style='text-align:right'>$rodas</td>
            <td>Refrigeração</td>
            <td style='text-align:right'>$refrigeracao</td>
        </tr>
        <tr>
            <td>Pneus Estepe</td>
            <td style='text-align:right'>$estepe</td>
            <td>Ventiladores do Equipamento</td>
            <td style='text-align:right'>$ventilador</td>
        </tr>
        <tr>
            <td>Estado Geral das Molas</td>
            <td style='text-align:right'>$molas</td>
        </tr>
        <tr>
            <td colspan='2'>Data da Chegada: $dataChegada</td>
            <td colspan='2'>Km Chegada: $kmRota</td>
        </tr>
        <tr>
            <td colspan='2'>Litros Abastecido: $ltAbastecido</td>
            <td colspan='2'>Valor Abastecido: $vlAbastecido</td>
        </tr>
        <tr>
            <td colspan='2'>Média de Consumo: $mediaConsumo Km/L</td>
            
        </tr>
        <tr>
            <th colspan='4'>Observações da Rota</th>
        </tr>
        <tr>
            <td colspan='4'>$obs</td>
        </tr>
        </table>
        <div style='width:100%; margin-top:10px'>
            <p style='border-top:1px solid #000; width:75%; text-align:center; margin-left:auto;margin-right:auto'>$motorista</p>
            <p style='border-top:1px solid #000; width:75%; text-align:center; margin-left:auto;margin-right:auto'>Ass. do Enc. do Transporte</p>
        </div>
    </body>
</html>
");

$mpdf->Output();

?>
