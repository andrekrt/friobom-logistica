<?php
session_start();
require("conexao.php");

if(isset($_SESSION['id']) && empty($_SESSION['id']) == false){
    $id = $_SESSION['id'];

    $sql = $db->query("SELECT * FROM usuarios WHERE idUser ='$id'");

    if($sql->rowCount() > 0 ){
        $dado = $sql->fetch();

        $nomeUsuario = $dado['nome'];
        $tipoUsuario = $dado['idTipo'];
        $codFornecedor = $dado['codInt'];
    }
    
}else{
    header("Location:index.php");
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Seja Bem-Vindo</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
        <link rel="manifest" href="favicon/site.webmanifest">
        <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>
        
        <div class="container-fluid ">
            <div class="cabecalho">
                <img src="assets/images/logo.png" alt="">
                <div class="titulo">Basto Mesquita Dist e Logistica</div>
            </div>

            <div class="menu">
                <img class="menu-mobile" src="assets/images/menu.png" alt="" onclick="abrirMenu()">
                <nav id="menuMobile">
                    <ul class="nav flex-column">
                        <li class="nav-item"> <a class="nav-link" href="index.php">Início</a></li>
                        <?php
                            
                        if($tipoUsuario==1){
                            echo "<li class='nav-item'> <a class='nav-link' href='form-fornecedor.php'>Novo Fornecedor</a> </li>";
                            echo "<li class='nav-item'> <a class='nav-link' href='lista-fornecedor.php'>Fornecedores</a> </li>";
                        }
                            
                        ?>
                        <li class="nav-item"> <a class="nav-link" href='sair.php'>Sair</a> </li>
                    </ul>
                </nav>
            </div>
            <form action="adicionando.php" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-grupo col-md-6 espaco">
                        <label for="cod-forn">Código Fornecedor</label>
                        <select name="cod-forn" id="" class="form-control">
                            <?php
                                if($tipoUsuario==1){
                                    $sql = $db->query("SELECT codInt, nome FROM usuarios WHERE idTipo =2 ORDER BY codInt ASC ");
                                    if($sql>0){
                                        $dados=$sql->fetchAll();{
                                            foreach($dados as $dado){
                                                echo "<option value='$dado[codInt]'>" .$dado['codInt']. " - ". $dado['nome'].  "</option>";
                                            }
                                        }
                                    }
                                }else{
                                    echo "<option value='$codFornecedor'>" .$codFornecedor.  "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-grupo col-md-6 espaco">
                        <label for="motivo">Motivo/Investimento/Gasto</label>
                        <select name="motivo" required id="motivo" class="form-control">
                            <option value=""></option>
                            <option value="de acordo">De Acordo</option>
                            <option value="Verba Mensal">Verbal Mensal</option>
                            <option value="Verba Sell Out">Verba Sell Out</option>
                            <option value="Negociação">Negociação</option>
                            <option value="Negociação Pontual (Cliente)">Negociação Pontual (Cliente)</option>
                            <option value="Descontos">Descontos</option>
                            <option value="Troca (Avaria/Def Fábrica)">Troca (Avaria/ Def Frábica)</option>
                            <option value="Troca (Clientes)">Troca (Clientes)</option>
                            <option value="Compra de Materiais">Compra de Materiais</option>
                            <option value="Ação de Mercado">Ação de Mercado</option>
                            <option value="Ação de Equipe">Ação de Equipe</option>
                            <option value="Preços NF'S Incorretos">Preços NF'S Incorretos</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-grupo col-md-6 espaco">
                        <label for="cod-cliente">Código Cliente/Beneficiado/Custeador</label>
                        <input type="text" required name="cod-cliente" class="form-control" id="cod-cliente">
                    </div>
                    <div class="form-grupo col-md-6 espaco">
                        <label for="nome-cliente">Nome do Cliente</label>
                        <input type="text" required name="nome-cliente" class="form-control" id="nome-cliente">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-grupo col-md-4 espaco">
                        <label for="obs">Produto/Obs</label>
                        <input type="text" required name="obs" class="form-control" id="obs">
                    </div>
                    <div class="form-grupo col-md-4 espaco">
                        <label for="form-pag">Forma de Pagamento</label>
                    <select name="form-pag" required id="form-pag" class="form-control">
                            <option value=""></option>
                            <option value="Bonificação">Bonificação</option>
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Desconto em Título">Desconto em Título</option>
                    </select>
                    </div>
                    <div class="form-group col-md-4 espaco">
                        <label for="valor">Valor R$</label>
                        <input required type="text" id="valor" class="form-control" name="valor">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6 espaco">
                        <label for="status">Status</label>
                        <select name="status" required id="status" class="form-control">
                            <option value=""></option>
                            <option value="Reservado p/ Receber"> Reservado para Receber</option>
                            <option value="Recebido">Recebido</option>
                            <option value="Pendente">Pendente</option>
                        </select>
                    </div>
                    <?php
               
                        if($tipoUsuario==1){
                            echo '<div class="input-group mb-3 col-md-6 centro-file">';
                            echo  '<div class="custom-file">';
                            echo    "<input type='file' class='custom-file-input' name='imagem'>";
                            echo    '<label class="custom-file-label">Escolher Arquivo</label>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="form-row">';
                            echo '<div class="form-group form-check form-check-inline espaco">';
                            echo    '<input type="radio" id="entrada" value="Entrada" name="tipo">';
                            echo    '<label for="entrada" class="form-check-label">Entrada</label>';
                            echo '</div>';
                            
                            

                        }
                    
                    ?>
                    
                               
                
                
              
               
                    <div class="form-group form-check form-check-inline espaco">
                        <input type="radio" id="saida" value="Saída" name="tipo">
                        <label for="saida" class="form-check-label">Saída</label>
                    </div> 
                </div>
                <button type="submit" name="entrada" class="btn btn-success"> Lançar Negociação</button>
            </form>
        </div>

        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/script.js"></script>
    </body>
</html>