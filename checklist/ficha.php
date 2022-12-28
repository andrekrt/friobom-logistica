<?php 
use Mpdf\Mpdf;
require_once __DIR__ . '/../vendor/autoload.php';
require("../conexao.php");


$id = filter_input(INPUT_GET,"id");

$sql = $db->prepare("SELECT * FROM checklist_apps LEFT JOIN checklist_apps_retorno02 ON checklist_apps.id=checklist_apps_retorno02.checksaida WHERE id = :id");
$sql->bindValue(':id',$id);

if($sql->execute()){
    $dados=$sql->fetchAll();

    foreach($dados as $dado){
        $hrTk = $dado['hr_tk'];
        $carregamento = $dado['carregamento_ret'];
        $tipo = $dado['tipo_checklist'];
        $placa = $dado['veiculo'];
        $dataCheck = date("d/m/Y", strtotime($dado['data']));
        $cabine = $dado['cabine'];
        $retrovisores = $dado['retrovisores'];
        $parabrisa = $dado['parabrisa'];
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
        $ventilador = $dado['ventilador'];
        $farolDianteiro = $dado['farol_dianteiro'];
        $farolTraseiro = $dado['farol_traseiro'];
        $farolNeblina = $dado['farol_neblina'];
        $farolAlto = $dado['farol_alto'];
        $luzPainel = $dado['painel'];
        $obs = nl2br($dado['obs']);

        //dados de retorno
        $hrTkRet = $dado['hr_tk_ret'];
        $carregamentoRet = $dado['carregamento_ret'];
        $tipoRet = $dado['tipo_checklist_ret'];
        $dataCheckRet = date("d/m/Y", strtotime($dado['data_ret']));
        $cabineRet = $dado['cabine_ret'];
        $retrovisoresRet = $dado['retrovisores_ret'];
        $parabrisaRet = $dado['parabrisa_ret'];
        $quebasolRet = $dado['quebra_sol_ret'];
        $bordoRet = $dado['bordo_ret'];
        $buzinaRet = $dado['buzina_ret'];
        $cintoRet = $dado['cinto_ret'];
        $extintorRet = $dado['extintor_ret'];
        $trianguloRet =$dado['triangulo_ret'];
        $macacoRet = $dado['macaco_ret'];
        $tanqueRet = $dado['tanque_ret'];
        $janelaRet = $dado['janelas_ret'];
        $bancoRet = $dado['banco_ret'];
        $portaRet = $dado['porta_ret'];
        $cambioRet = $dado['cambio_ret'];
        $setaRet = $dado['seta_ret'];
        $luzFreioRet = $dado['luz_freio_ret'];
        $luzReRet = $dado['luz_re_ret'];
        $alertaRet = $dado['alerta_ret'];
        $luzTetoRet =$dado['luz_teto_ret'];
        $faixasRet = $dado['faixas_ret'];
        $pneusRet = $dado['pneus_ret'];
        $rodasRet = $dado['rodas_ret'];
        $estepeRet = $dado['estepe_ret'];
        $molasRet = $dado['molas_ret'];
        $caboRet = $dado['cabo_forca_ret'];
        $refrigeracaoRet = $dado['refrigeracao_ret'];
        $ventiladorRet = $dado['ventilador_ret'];
        $farolDianteiroRet = $dado['farol_dianteiro_ret'];
        $farolTraseiroRet = $dado['farol_traseiro_ret'];
        $farolNeblinaRet = $dado['farol_neblina_ret'];
        $farolAltoRet = $dado['farol_alto_ret'];
        $luzPainelRet = $dado['painel_ret'];
        $obsRet = nl2br($dado['obs_ret']);
        
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
                <td>Placa: $placa</td>
                <td>Carregamento: $carregamento</td>
                <td>Hora do Tk Saída: $hrTk </td>
            </tr>
            <tr>
                <td>Hora do Tk Retorno: $hrTkRet </td>
                <td>Data do CheckList Saída: $dataCheck</td>
                <td>Data do CheckList Retorno: $dataCheckRet</td>
            </tr>
        </table>

        <table border='1' style='width:100%;font-size:9pt'>
            <thead>
                <tr>
                    <th colspan='2' style='text-align:center'>EQUIPAMENTOS OBRIGATÓRIO E PRÓPRIOS (SAÍDA)</th>
                    <th colspan='2' style='text-align:center'>EQUIPAMENTOS OBRIGATÓRIO E PRÓPRIOS (RETORNO)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Limpeza da Cabine</td>
                    <td style='text-align:right'>$cabine</td>
                    <td>Limpeza da Cabine</td>
                    <td style='text-align:right'>$cabineRet</td>
                </tr>
                <tr>
                    <td>Retrovisores</td>
                    <td style='text-align:right'>$retrovisores</td>
                    <td>Retrovisores</td>
                    <td style='text-align:right'>$retrovisoresRet</td>
                </tr>
                <tr>
                    <td>Limpador e Lavador de Para-Brisa</td>
                    <td style='text-align:right'>$parabrisa</td>
                    <td>Limpador e Lavador de Para-Brisa</td>
                    <td style='text-align:right'>$parabrisaRet</td>
                </tr>
                <tr>
                    <td>Quebra Sol</td>
                    <td style='text-align:right'>$quebasol</td>
                    <td>Quebra Sol</td>
                    <td style='text-align:right'>$quebasolRet</td>
                </tr>
                <tr>
                    <td>Velocímetro/Tacog./Comp. de Bordo</td>
                    <td style='text-align:right'>$bordo</td>
                    <td>Velocímetro/Tacog./Comp. de Bordo</td>
                    <td style='text-align:right'>$bordoRet</td>
                </tr>
                <tr>
                    <td>Buzina</td>
                    <td style='text-align:right'>$buzina</td>
                    <td>Buzina</td>
                    <td style='text-align:right'>$buzinaRet</td>
                </tr>
                <tr>
                    <td>Cinto de Segurança</td>
                    <td style='text-align:right'>$cinto</td>
                    <td>Cinto de Segurança</td>
                    <td style='text-align:right'>$cintoRet</td>
                </tr>
                <tr>
                    <td>Extintor de Incêndio</td>
                    <td style='text-align:right'>$extintor</td>
                    <td>Extintor de Incêndio</td>
                    <td style='text-align:right'>$extintorRet</td>
                </tr>
                <tr>
                    <td>Triângulo de Sinalização</td>
                    <td style='text-align:right'>$triangulo</td>
                    <td>Triângulo de Sinalização</td>
                    <td style='text-align:right'>$trianguloRet</td>
                </tr>
                <tr>
                    <td>Macaco e Chave de Roda</td>
                    <td style='text-align:right'>$macaco</td>
                    <td>Macaco e Chave de Roda</td>
                    <td style='text-align:right'>$macacoRet</td>
                </tr>
                <tr>
                    <td>Portas e Tampas do Tanque de Comb.</td>
                    <td style='text-align:right'>$tanque</td>
                    <td>Portas e Tampas do Tanque de Comb.</td>
                    <td style='text-align:right'>$tanqueRet</td>
                </tr>
                <tr>
                    <td>Vidros e Janelas</td>
                    <td style='text-align:right'>$janela</td>
                    <td>Vidros e Janelas</td>
                    <td style='text-align:right'>$janelaRet</td>
                </tr>
                <tr>
                    <td>Forro do Banco</td>
                    <td style='text-align:right'>$banco</td>
                    <td>Forro do Banco</td>
                    <td style='text-align:right'>$bancoRet</td>
                </tr>
                <tr>
                    <td>Maçaneta da Porta</td>
                    <td style='text-align:right'>$porta</td>
                    <td>Maçaneta da Porta</td>
                    <td style='text-align:right'>$portaRet</td>
                </tr>
                <tr>
                    <td>Alavanca do Câmbio</td>
                    <td style='text-align:right'>$cambio</td>
                    <td>Alavanca do Câmbio</td>
                    <td style='text-align:right'>$cambioRet</td>
                </tr>
            </tbody>
        </table>
        <table border='1' style='width:100%;font-size:8pt'>
        <tr>
            <th colspan='2'>SISTEMA DE SINALIZAÇÃO(SAÍDA)</th>
            <th colspan='2'>SISTEMA DE SINALIZAÇÃO(RETORNO)</th>
            <th colspan='2'>SISTEMA DE ILUMINAÇÃO(SAÍDA)</th>
            <th colspan='2'>SISTEMA DE ILUMINAÇÃO(RETORNO)</th>
        </tr>
        <tr style=''>
            <td>Lanternas Indicadoras de Direção</td>
            <td style='text-align:right'>$seta</td>
            <td>Lanternas Indicadoras de Direção</td>
            <td style='text-align:right'>$setaRet</td>
            <td>Farol Dianteiro</td>
            <td style='text-align:right'>$farolDianteiro</td>
            <td>Farol Dianteiro</td>
            <td style='text-align:right'>$farolDianteiroRet</td>
        </tr>
        <tr>
            <td>Lanternas de Freio e Elevada</td>
            <td style='text-align:right'>$luzFreio</td>
            <td>Lanternas de Freio e Elevada</td>
            <td style='text-align:right'>$luzFreioRet</td>
            <td>Farol Traseiro</td>
            <td style='text-align:right'>$farolTraseiro</td>
            <td>Farol Traseiro</td>
            <td style='text-align:right'>$farolTraseiroRet</td>
        </tr>
        <tr>
            <td>Lanterna de Marcha Ré</td>
            <td style='text-align:right'>$luzRe</td>
            <td>Lanterna de Marcha Ré</td>
            <td style='text-align:right'>$luzReRet</td>
            <td>Farol de Neblina</td>
            <td style='text-align:right'>$farolNeblina</td>
            <td>Farol de Neblina</td>
            <td style='text-align:right'>$farolNeblinaRet</td>
        </tr>
        <tr>
            <td>Pisca Alerta</td>
            <td style='text-align:right'>$alerta</td>
            <td>Pisca Alerta</td>
            <td style='text-align:right'>$alertaRet</td>
            <td>Farol de Longo Alcance(Alto)</td>
            <td style='text-align:right'>$farolAlto</td>
            <td>Farol de Longo Alcance(Alto)</td>
            <td style='text-align:right'>$farolAltoRet</td>
        </tr>
        <tr>
            <td>Luzes de Sinalização Intermitente do Teto</td>
            <td style='text-align:right'>$luzTeto</td>
            <td>Luzes de Sinalização Intermitente do Teto</td>
            <td style='text-align:right'>$luzTetoRet</td>
            <td>Luzes do Painel</td>
            <td style='text-align:right'>$luzPainel</td>
            <td>Luzes do Painel</td>
            <td style='text-align:right'>$luzPainelRet</td>
        </tr>
        <tr>
            <td>Faixas Refletivas / Retrorefletivas</td>
            <td style='text-align:right'>$faixas</td>
            <td>Faixas Refletivas / Retrorefletivas</td>
            <td style='text-align:right'>$faixasRet</td>
        </tr>
        
        <tr>
            <th colspan='2'>PNEUS E SISTEMA DE SUSPENSÃO(SAÍDA)</th>
            <th colspan='2'>PNEUS E SISTEMA DE SUSPENSÃO(RETORNO)</th>
            <th colspan='2'>EQUIPAMENTOS DE REFRIGERAÇÃO(SAÍDA)</th>
            <th colspan='2'>EQUIPAMENTOS DE REFRIGERAÇÃO(RETORNO)</th>
        </tr>
        <tr>
            <td>Estado Geral dos Pneus</td>
            <td style='text-align:right'>$pneus</td>
            <td>Estado Geral dos Pneus</td>
            <td style='text-align:right'>$pneusRet</td>
            <td>Cabo de Força</td>
            <td style='text-align:right'>$cabo</td>
            <td>Cabo de Força</td>
            <td style='text-align:right'>$caboRet</td>
        </tr>
        <tr>
            <td>Estado Geral da Rodas</td>
            <td style='text-align:right'>$rodas</td>
            <td>Estado Geral da Rodas</td>
            <td style='text-align:right'>$rodasRet</td>
            <td>Refrigeração</td>
            <td style='text-align:right'>$refrigeracao</td>
            <td>Refrigeração</td>
            <td style='text-align:right'>$refrigeracaoRet</td>
        </tr>
        <tr>
            <td>Pneus Estepe</td>
            <td style='text-align:right'>$estepe</td>
            <td>Pneus Estepe</td>
            <td style='text-align:right'>$estepeRet</td>
            <td>Ventiladores do Equipamento</td>
            <td style='text-align:right'>$ventilador</td>
            <td>Ventiladores do Equipamento</td>
            <td style='text-align:right'>$ventiladorRet</td>
        </tr>
        <tr>
            <td>Estado Geral das Molas</td>
            <td style='text-align:right'>$molas</td>
            <td>Estado Geral das Molas</td>
            <td style='text-align:right'>$molasRet</td>
        </tr>      
        <tr>
            <th colspan='4' >Observações da Rota(SAÍDA)</th>
            <th colspan='4' >Observações da Rota(RETORNO)</th>
        </tr>
        <tr>
            <td colspan='4' style='height:100px'>$obs</td>
            <td colspan='4' style='height:100px'>$obsRet</td>
        </tr>
        </table>
        <div style='width:100%; margin-top:10px'>
            
            <p style='border-top:1px solid #000; width:75%; text-align:center; margin-left:auto;margin-right:auto'>Ass. do Enc. do Transporte</p>
        </div>
    </body>
</html>
");

$mpdf->Output();

?>
