<?php 

include("../../conexao.php");

$veiculo = $_REQUEST['veiculo'];

$sql = $db->query("SELECT * FROM pneus WHERE veiculo='$veiculo'");
$pneus = $sql->fetchAll();

foreach($pneus as $pneu){
    $pneuArray[] = array(
        'idpneu'=>$pneu['idpneus'],
        'fogo'=>$pneu['num_fogo'],
        'km'=>$pneu['km_inicial']
    );
}

echo(json_encode($pneuArray));

?>