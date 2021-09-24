<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==1 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];

    $pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM viagem ORDER BY data_carregamento DESC LIMIT 200");
    
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
        <title>Despesas</title>
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
                        <img src="../assets/images/icones/despesas.png" alt="">
                    </div>
                    <div class="title">
                        <h2> Despesas Lançadas </h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <div class="filtro">
                        <form action="" class="form-inline" method="post">
                            <div class="form-row">
                                <select name="carregamento" id="" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $filtro = $db->query("SELECT num_carregemento FROM viagem ORDER BY num_carregemento DESC");
                                    if ($filtro->rowCount() > 0) {
                                        $dados = $filtro->fetchAll();
                                        foreach ($dados as $dado) {

                                    ?>
                                            <option value="<?php echo $dado['num_carregemento'] ?>"> <?php echo $dado['num_carregemento'] ?> </option>
                                    <?php

                                        }
                                    }

                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">Nº Carregamento</th>
                                    <th scope="col" class="text-center text-nowrap">Placa Veículo</th>
                                    <th scope="col" class="text-center text-nowrap">Motorista</th>
                                    <th scope="col" class="text-center text-nowrap">Rota</th>
                                    <th scope="col" class="text-center text-nowrap">Data Carregamento</th>
                                    <th scope="col" class="text-center text-nowrap"> Ações </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $totalDespesas = $selecionar->rowCount();
                                $qtdPorPagina = 10;
                                $numPaginas = ceil($totalDespesas / $qtdPorPagina);
                                $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                if (isset($_POST['filtro']) && empty($_POST['carregamento']) == false) {
                                    $carregamento = filter_input(INPUT_POST, 'carregamento');
                                    $filtrado = $db->query("SELECT * FROM viagem WHERE num_carregemento = '$carregamento'");

                                    if ($filtrado->rowCount() > 0) {
                                        $dados = $filtrado->fetchAll();
                                        foreach ($dados as $dado) {
                                            $idDespesa = $dado['iddespesas'];
                                ?>
                                            <tr>
                                                <td scope="col" class="text-center text-nowrap"> <?php echo $dado['num_carregemento']; ?> </td>
                                                <td scope="col" class="text-center text-nowrap"> <?php echo $dado['placa_veiculo']; ?> </td>
                                                <td scope="col" class="text-center text-nowrap"> <?php echo $dado['nome_motorista']; ?> </td>
                                                <td scope="col" class="text-center text-nowrap"> <?php echo $dado['nome_rota'] ?> </td>
                                                <td scope="col" class="text-center text-nowrap"> <?php echo date("d/m/Y", strtotime($dado['data_carregamento']));  ?> </td>
                                                <td scope="col" class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Ações
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" target="_blank" href="gerar-pdf.php?id=<?php echo $idDespesa ?>">Imprimir</a>
                                                            <?php if($tipoUsuario==99):?>
                                                                <a class="dropdown-item" href="excluir.php?id=<?=$idDespesa ?>">Excluir</a>
                                                            <?php endif;?>
                                                            <a class="dropdown-item" href="form-atualiza.php?id=<?=$idDespesa ?>">Editar</a>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        <?php

                                        }
                                    }
                                } else {
                                    $limitado = $db->query("SELECT * FROM viagem ORDER BY data_carregamento DESC LIMIT $paginaInicial,$qtdPorPagina ");

                                    if ($limitado->rowCount() > 0) {
                                        $dados = $limitado->fetchAll();
                                        foreach ($dados as $dado) {
                                            $idDespesa = $dado['iddespesas'];
                                        ?>
                                            <tr>
                                                <td scope="col" class="text-center text-nowrap"> <?php echo $dado['num_carregemento']; ?> </td>
                                                <td scope="col" class="text-center text-nowrap"> <?php echo $dado['placa_veiculo']; ?> </td>
                                                <td scope="col" class="text-center text-nowrap"> <?php echo $dado['nome_motorista']; ?> </td>
                                                <td scope="col" class="text-center text-nowrap"> <?php echo $dado['nome_rota'] ?> </td>
                                                <td scope="col" class="text-center text-nowrap"> <?php echo date("d/m/Y H:i", strtotime($dado['data_carregamento']));  ?> </td>
                                                <td scope="col" class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Ações
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" target="_blank" href="gerar-pdf.php?id=<?php echo $idDespesa ?>">Imprimir</a>
                                                            <?php if($tipoUsuario==99):?>
                                                                <a class="dropdown-item" href="excluir.php?id=<?=$idDespesa ?>">Excluir</a>
                                                            <?php endif;?>
                                                            <a class="dropdown-item" href="form-atualiza.php?id=<?=$idDespesa ?>">Editar</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                <?php

                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php

                        $paginaAnterior = $pagina - 1;
                        $paginaPosterior = $pagina + 1;

                    ?>
                    <nav aria-label="Navegação de página exemplo" class="paginacao">
                        <ul class="pagination">
                            <li class="page-item">
                                <?php
                                if ($paginaAnterior != 0) {
                                    echo "<a class='page-link' href='veiculos.php?pagina=$paginaAnterior' aria-label='Anterior'>
                                        <span aria-hidden='true'>&laquo;</span>
                                        <span class='sr-only'>Anterior</span>
                                    </a>";
                                } else {
                                    echo "<a class='page-link' aria-label='Anterior'> 
                                            <span aria-hidden='true'>&laquo;</span>
                                            <span class='sr-only'>Anterior</span>
                                        </a>";
                                }
                                ?>

                            </li>
                            <?php
                            for ($i = 1; $i < $numPaginas + 1; $i++) {
                                echo "<li class='page-item'><a class='page-link' href='despesas.php?pagina=$i'>$i</a></li>";
                            }
                            ?>
                            <li class="page-item">
                                <?php
                                if ($paginaPosterior <= $numPaginas) {
                                    echo " <a class='page-link' href='despesas.php?pagina=$paginaPosterior' aria-label='Próximo'>
                                        <span aria-hidden='true'>&raquo;</span>
                                        <span class='sr-only'>Próximo</span>
                                    </a>";
                                } else {
                                    echo " <a class='page-link' aria-label='Próximo'>
                                                <span aria-hidden='true'>&raquo;</span>
                                                <span class='sr-only'>Próximo</span>
                                        </a> ";
                                }
                                ?>

                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
    </body>
</html>