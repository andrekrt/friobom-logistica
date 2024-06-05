<?php

require_once 'conexao-on.php';

$sql = $db->query("SHOW TABLES");

$tabelas = $sql->fetchAll(PDO::FETCH_ASSOC);

// var_dump($tabelas);

foreach($tabelas as $tabela){
    $nome_tabela = $tabela['Tables_in_friobo99_portaria'];
    
    if($nome_tabela!='filiais'){
        $sqlAlter =$db->query("ALTER TABLE $nome_tabela ADD COLUMN filial INT DEFAULT 2");
        if($sqlAlter){
            echo "Tabela: " . $nome_tabela . "Filial Adicionada" . "<hr>";

            $sqlKey =$db->query("ALTER TABLE $nome_tabela ADD FOREIGN KEY (`filial`) REFERENCES `filiais`(`cod_filial`) ON DELETE RESTRICT ON UPDATE RESTRICT");
            if($sqlKey){
                echo "Chave Estrangeira registrda na tabela $nome_tabela" . "<hr>";
            }else{
                echo "Erro ao registra chave estrangeira na tabela $nome_tabela" . "<hr>";
                var_dump($db->errorInfo());
            }
        }else{
           echo "Erro na adição da filial em " . $nome_tabela . "<hr>";
           var_dump($db->errorInfo()); 
        }
    }
    
}