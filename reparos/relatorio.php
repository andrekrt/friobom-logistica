<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] !=  3){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];
    
}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dados</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="../assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>
        <div class="container-fluid corpo">
            <?php require('../menu-lateral.php') ?>
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                    <div class="icone-menu-superior">
                        <img src="../assets/images/icones/reparos.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Valores Já Gastos</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <div class="filtro">
                        <!-- iniciando filtro por veiculo -->
                        <form action="" class="form-inline" method="post">
                            <div class="form-row">
                                <div class="form-group">
                                    <input type="date" name="dataInicial" class="form-control"> 
                                    <span style="margin-left:10px; margin-right:10px; color:#fff"> a </span> 
                                    <input type="date" name="dataFinal" class="form-control">
                                    <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                        <!-- fim filtro por veiculo -->
                        <a href="relatorio-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                    </div>
                    <div class="table-responsivve">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center"> Gasto Total </th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $dataInicial = filter_input(INPUT_POST, 'dataInicial')?filter_input(INPUT_POST, 'dataInicial'):"2020-01-01";
                                    $dataFinal = filter_input(INPUT_POST, 'dataFinal')?filter_input(INPUT_POST, 'dataFinal'):"2050-12-31";
                                    
                                    $sql =$db->query("SELECT SUM(solicitacoes.valor) + SUM(solicitacoes02.valor) AS total FROM solicitacoes INNER JOIN solicitacoes02 ON solicitacoes.id = solicitacoes02.idSocPrinc WHERE dataAtual BETWEEN '$dataInicial' AND '$dataFinal'");

                                    if($sql){
                                        $dados = $sql->fetchAll();
                                        foreach($dados as $dado){
                                ?>
                                    <tr>
                                        <td class="text-center"> <?php echo "R$ ". number_format($dado['total'], 2, ",", ".")  ?> </td>
                                    </tr>
                                <?php            
                                        }
                                    }
                                
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
    </body>
</html>