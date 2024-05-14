<?php

function buscarMeta($tipo, $data){
    session_start();
    include '../conexao.php';
    $filial = $_SESSION['filial'];
    switch ($tipo) {
        case 'Check-List Saída':
            $sql = $db->prepare("SELECT * FROM checklist_apps WHERE data = :dataCheck AND filial=:filial");
            $sql->bindValue(':dataCheck', $data);
            $sql->bindValue(':filial',$filial);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Check-List Retorno':
            $sql = $db->prepare("SELECT * FROM checklist_apps_retorno02 WHERE data_ret = :dataCheck AND filial=:filial");
            $sql->bindValue(':dataCheck', $data);
            $sql->bindValue(':filial',$filial);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Registor de O.S.':
            $sql = $db->prepare("SELECT * FROM ordem_servico WHERE DATE(data_abertura) = :dataCheck AND filial=:filial");
            $sql->bindValue(':dataCheck', $data);
            $sql->bindValue(':filial',$filial);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Inventário de Peças':
            $sql = $db->prepare("SELECT * FROM inventario_almoxarifado WHERE data_inv = :dataCheck AND filial=:filial");
            $sql->bindValue(':dataCheck', $data);
            $sql->bindValue(':filial',$filial);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Coleta de Suco Pneus':
            $sql = $db->prepare("SELECT * FROM sucos WHERE DATE(data_medicao) = :dataCheck AND filial=:filial");
            $sql->bindValue(':dataCheck', $data);
            $sql->bindValue(':filial',$filial);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Alinhamento e Balanciamento':
            $sql = $db->prepare("SELECT * FROM alinhamentos_veiculo WHERE data_alinhamento = :dataCheck AND filial=:filial ");
            $sql->bindValue(':dataCheck', $data);
            $sql->bindValue(':filial',$filial);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Rodízio de Pneus':
            $sql = $db->prepare("SELECT * FROM rodizio_pneu WHERE data_rodizio = :dataCheck AND filial=:filial");
            $sql->bindValue(':dataCheck', $data);
            $sql->bindValue(':filial',$filial);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Ferrar Pneus':
            $sql = $db->prepare("SELECT * FROM pneus WHERE DATE(data_cadastro) = :dataCheck AND filial=:filial ");
            $sql->bindValue(':dataCheck', $data);
            $sql->bindValue(':filial',$filial);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        default:
            $qtd = 0;
            break;
    }

    return $qtd;
}



//echo buscarMeta('Check-List Saída', '2023-03-01');

?>