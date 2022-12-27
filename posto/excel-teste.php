<?php 

// session_start();

// ob_start();

// include_once '../conexao-on.php';

// $sql = $db->prepare("SELECT * FROM combustivel_saida LEFT JOIN usuarios ON combustivel_saida.usuario = usuarios.idusuarios ");

// if($sql->execute()){
//     header('Content-Type: text/csv; charset=utf-8');
//     header('Content-Disposition: attachment; filename=arquivo.csv');
//     $resultado=fopen("php://output",'w');
//     $cabecalho = ['ID', 'Data Abastecimento', 'Litros Abastecimento', 'R$/Lt','Valor Total', 'Carregamento', 'Km', 'Placa', 'Rota', 'Motorista', 'Tipo de Abastecimento', 'Lançado'];

//     fputcsv($resultado, $cabecalho, ';');

//     $dados = $sql->fetchAll();

//     foreach($dados as $dado){
//         fputcsv($resultado, $dado, ';');
//     }

// }else{
//     print_r($sql->errorInfo());
// }




session_start(); // Iniciar a sessão

// Limpar o buffer
ob_start();

// Incluir a conexão com BD
include_once '../conexao-on.php';

// QUERY para recuperar os registros do banco de dados
$query_usuarios = "SELECT * FROM combustivel_saida LEFT JOIN usuarios ON combustivel_saida.usuario = usuarios.idusuarios";

// Preparar a QUERY
$result_usuarios = $db->prepare($query_usuarios);

// Executar a QUERY
$result_usuarios->execute();

// Acessa o IF quando encontrar registro no banco de dados
if(($result_usuarios) and ($result_usuarios->rowCount() != 0)){

    // Aceitar csv ou texto 
    header('Content-Type: text/csv; charset=utf-8');

    // Nome arquivo
    header('Content-Disposition: attachment; filename=arquivo.csv');

    // Gravar no buffer
    $resultado = fopen("php://output", 'w');

    // Criar o cabeçalho do Excel - Usar a função mb_convert_encoding para converter carateres especiais
    $cabecalho =  ['ID', 'Data Abastecimento', 'Litros Abastecimento', 'R$/Lt','Valor Total', 'Carregamento', 'Km', 'Placa', 'Rota', 'Motorista', 'Tipo de Abastecimento', 'Lançado'];

    // Escrever o cabeçalho no arquivo
    fputcsv($resultado, $cabecalho, ';');
    
    $dados = $result_usuarios;

    // Ler os registros retornado do banco de dados
    foreach($dados as $dado){

        // Escrever o conteúdo no arquivo
        fputcsv($resultado, $dado , ';');

    }

    // Fechar arquivo
    //fclose($resultado);
}else{ // Acessa O ELSE quando não encontrar nenhum registro no BD
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Nenhum usuário encontrado!</p>";
    header("Location: index.php");
}

?>