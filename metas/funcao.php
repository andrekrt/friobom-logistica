<?php

function buscarMeta($tipo, $data){
    include '../conexao.php';

    switch ($tipo) {
        case 'Check-List Saída':
            $sql = $db->prepare("SELECT * FROM checklist_apps WHERE data = :dataCheck ");
            $sql->bindValue(':dataCheck', $data);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Check-List Retorno':
            $sql = $db->prepare("SELECT * FROM checklist_apps_retorno02 WHERE data_ret = :dataCheck ");
            $sql->bindValue(':dataCheck', $data);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Registor de O.S.':
            $sql = $db->prepare("SELECT * FROM ordem_servico WHERE DATE(data_abertura) = :dataCheck ");
            $sql->bindValue(':dataCheck', $data);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Inventário de Peças':
            $sql = $db->prepare("SELECT * FROM inventario_almoxarifado WHERE data_inv = :dataCheck ");
            $sql->bindValue(':dataCheck', $data);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Coleta de Suco Pneus':
            $sql = $db->prepare("SELECT * FROM sucos WHERE DATE(data_medicao) = :dataCheck ");
            $sql->bindValue(':dataCheck', $data);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Alinhamento e Balanciamento':
            $sql = $db->prepare("SELECT * FROM alinhamentos_veiculo WHERE data_alinhamento = :dataCheck ");
            $sql->bindValue(':dataCheck', $data);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Rodízio de Pneus':
            $sql = $db->prepare("SELECT * FROM rodizio_pneu WHERE data_rodizio = :dataCheck ");
            $sql->bindValue(':dataCheck', $data);
            $sql->execute();
            $qtd = $sql->rowCount();
            break;
        case 'Ferrar Pneus':
            $sql = $db->prepare("SELECT * FROM pneus WHERE DATE(data_cadastro) = :dataCheck ");
            $sql->bindValue(':dataCheck', $data);
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