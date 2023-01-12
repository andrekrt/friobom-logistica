<?php

session_start();

ob_start();

include_once '../conexao.php';

$sql = $db->prepare("SELECT 
    id, veiculo, hr_tk, data, cabine, retrovisores, parabrisa, quebra_sol, bordo, buzina, cinto, extintor, triangulo, macaco, tanque, janelas, banco, porta, cambio, seta, luz_freio, luz_re, alerta, luz_teto, faixas, farol_dianteiro, farol_traseiro, farol_neblina, farol_alto, painel, rodas, pneus, estepe, molas, cabo_forca, refrigeracao, ventilador, obs, hr_tk_ret, carregamento_ret, data_ret, cabine_ret, retrovisores_ret, parabrisa_ret, quebra_sol_ret, bordo_ret, buzina_ret, cinto_ret, extintor_ret, triangulo_ret, macaco_ret, tanque_ret, janelas_ret, banco_ret, porta_ret, cambio_ret, seta_ret, luz_freio_ret, luz_re_ret, alerta_ret, luz_teto_ret, faixas_ret, farol_dianteiro_ret, farol_traseiro_ret, farol_neblina_ret, farol_alto_ret, painel_ret, rodas_ret, pneus_ret, estepe_ret, molas_ret, cabo_forca_ret, refrigeracao_ret, ventilador_ret, obs_ret
 FROM checklist_apps LEFT JOIN checklist_apps_retorno02 ON checklist_apps.id = checklist_apps_retorno02.checksaida");

if($sql->execute()){
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=checklist.csv');

    $resultado = fopen("php://output", 'w');

    $cabecalho = [
        "ID",
        mb_convert_encoding("Veículo", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Hora Tk Saída", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Data Saída", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Limpeza da Cabine (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Retrovisores (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Limpador e Lavador de Para-brisa (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Quebra Sol Saída", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Velocímetro/Tacog/Comp. de Bordo (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Buzina (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Cinto de Segurança (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Extintor de Incêndio (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Triângulo de Sinalização (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Macaco e Chave de Roda (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Portas e Tampas do Tanque Comb. (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Vidros e Janelas (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Forro do Banco (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Maçaneta da Porta (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Alavanca do Câmbio (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Lanternas Indicadoreas de Direção (Saída)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Lanternas de Freio e Elevada (Saída)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Lanterna de Marcha Ré (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Pisca Alerta (Saída)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Luzes de Sinalização Intermitente do Teto (Saída)", 'ISO-8859-1', 'UTF-8')  ,
        mb_convert_encoding("Faixas Refletivas/Retrofletivas (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Farol Dianteiro (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Farol Traseiro (Saída)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Farol de Neblina (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Farol Alto (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Luzes do Painel (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Pneus (Saída)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Rodas (Saída)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Estepe (Saída)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Molas (Saída)", 'ISO-8859-1', 'UTF-8')  ,
        mb_convert_encoding("Cabo de Força, (Saída)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Refrigeração (Saída)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Ventiladores do Equipamento (Saída)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Obs (Saída)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Hora Tk Retorno ", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Carregamento ", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Data Retorno", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Limpeza da Cabine (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Retrovisores (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Limpador e Lavador de Para-brisa (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Quebra Sol (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Velocímetro/Tacog/Comp. de Bordo (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Buzina (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Cinto de Segurança (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Extintor de Incêndio (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Triângulo de Sinalização (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Macaco e Chave de Roda (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Portas e Tampas do Tanque Comb. (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Vidros e Janelas (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Forro do Banco (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Maçaneta da Porta (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Alavanca do Câmbio (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Lanternas Indicadoreas de Direção (Retorno)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Lanternas de Freio e Elevada (Retorno)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Lanterna de Marcha Ré (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Pisca Alerta (Retorno)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Luzes de Sinalização Intermitente do Teto (Retorno)", 'ISO-8859-1', 'UTF-8')  ,
        mb_convert_encoding("Faixas Refletivas/Retrofletivas (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Farol Dianteiro (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Farol Traseiro (Retorno)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Farol de Neblina (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Farol Alto (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Luzes do Painel (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Pneus (Retorno)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Rodas (Retorno)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Estepe (Retorno)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Molas (Retorno)", 'ISO-8859-1', 'UTF-8')  ,
        mb_convert_encoding("Cabo de Força, (Retorno)", 'ISO-8859-1', 'UTF-8') ,
        mb_convert_encoding("Refrigeração (Retorno)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Ventiladores do Equipamento (Retorno)", 'ISO-8859-1', 'UTF-8'),
        mb_convert_encoding("Obs (Retorno)", 'ISO-8859-1', 'UTF-8'),
    ];

    fputcsv($resultado, $cabecalho, ';');

    while($registro = $sql->fetch(PDO::FETCH_ASSOC)){
        fputcsv($resultado,mb_convert_encoding($registro,'ISO-8859-1', 'UTF-8'), ';');
    }

}else{
    print_r($sql->errorInfo());
}

?>