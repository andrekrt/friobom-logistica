<?php 

include_once "../conex.php";

$url = "http://educacao.dadosabertosbr.com/api/cidades/ma";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$pokemons = json_decode(curl_exec($ch));

for($i=0;$i<count($pokemons);$i++){
    $cidade = explode(":", $pokemons[$i]);
    $sql=$db->prepare("INSERT INTO cidades (nome,estado) VALUES(:cidade, :estado)");
    $sql->bindValue(':cidade', $cidade[1]);
    $sql->bindValue(':estado', 'MA');
    $sql->execute();
    
    echo $cidade[1]."<br>";
}