<?php 

include_once "../conexao.php";

$url = "http://educacao.dadosabertosbr.com/api/cidades/pi";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$pokemons = json_decode(curl_exec($ch));

for($i=0;$i<count($pokemons);$i++){
    $cidade = explode(":", $pokemons[$i]);
    $sql=$db->prepare("INSERT INTO cidades (nome_cidade, estado) VALUES(:cidade, :estado)");
    $sql->bindValue(':cidade', $cidade[1]);
    $sql->bindValue(':estado', 'PI');
    $sql->execute();
    
    echo $cidade[1]."<br>";
}