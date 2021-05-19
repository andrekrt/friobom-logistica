<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] == 3 || $_SESSION['tipoUsuario'] == 4 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];
    $idUsuario = $_SESSION['idUsuario'];

    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
    
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
        <title>Solicitações</title>
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
            <div class="menu-lateral" id="menu-lateral">
                <div class="logo">  
                    <img src="../assets/images/logo.png" alt="">
                </div>
                <div class="opcoes">
                    <div class="item">
                        <a href="../index.php">
                            <img src="../assets/images/menu/inicio.png" alt="">
                        </a>
                    </div>
                    <div class="item">
                        <a class="" onclick="menuVeiculo()">
                            <img src="../assets/images/menu/veiculos.png" alt="">
                        </a>
                        <nav id="submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../veiculos/veiculos.php"> Veículos </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../veiculos/form-veiculos.php"> Cadastrar Veículo </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../veiculos/revisao.php"> Revisões </a> </li>
                                <li class="nav-item"> <a href="../veiculos/relatorio.php" class="na-link">Despesas por Veículo</a> </li>
                                <li class="nav-item"> <a href="../veiculos/gastos.php" class="na-link">Relatório</a> </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="item">
                        <a onclick="menuRota()">
                            <img src="../assets/images/menu/rotas.png" alt="">
                        </a>
                        <nav id="submenuRota">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../rotas/rotas.php"> Rotas </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../rotas/form-rota.php"> Cadastrar Rota </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../rotas/relatorio.php"> Relatório</a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuMotorista()">
                            <img src="../assets/images/menu/motoristas.png" alt="">
                        </a>
                        <nav id="submenuMotorista">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../motoristas/motoristas.php"> Motoristas </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../motoristas/form-motorista.php"> Cadastrar Motorista </a> </li>
                                <li class="nav-item"> <a href="../motoristas/dados.php" class="nav-link"> Relatório</a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuDespesas()">
                            <img src="../assets/images/menu/despesas.png" alt="">
                        </a>
                        <nav id="submenuDespesa">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../controle-despesas/despesas.php"> Despesas </a> </li><li class="nav-item"> <a class="nav-link" href="../controle-despesas/complementos.php"> Complementos </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../controle-despesas/form-lancar-despesas.php"> Lançar Despesa </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../controle-despesas/gerar-planilha.php"> Planilha de Despesas </a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuCheck()">
                            <img src="../assets/images/menu/check-list.png" alt="">
                        </a>
                        <nav id="submenuCheck">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../check-list/check-list.php"> Check-Lists </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../check-list/form-check.php"> Fazer Check-List </a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuReparos()">
                            <img src="../assets/images/menu/reparos.png" alt="">
                        </a>
                        <nav id="submenuReparos">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../reparos/solicitacoes.php"> Solicitações </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../reparos/form-solicitacao.php"> Nova Solicitação </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../reparos/relatorio.php"> Valores Gastos</a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuCarregamentos()">
                            <img src="../assets/images/menu/carregamentos.png" alt="">
                        </a>
                        <nav id="submenuCarregamentos">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a href="../carregamentos/carregamentos.php" class="nav-link"> Carregamentos </a> </li>
                                <li class="nav-item"> <a href="../carregamentos/form-carregamento.php" class="nav-link"> Novo Carregamento </a> </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="item">
                        <a onclick="menuAlmoxerifado()">
                            <img src="../assets/images/menu/almoxerifado.png" alt="">
                        </a>
                        <nav id="submenuAlmoxerifado">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a href="../almoxerifado/pecas.php" class="nav-link"> Estoque </a> </li>
                                <li class="nav-item"> <a href="../almoxerifado/entradas.php" class="nav-link"> Entrada </a> </li>
                                <li class="nav-item"> <a href="../almoxerifado/saidas.php" class="nav-link"> Saída </a> </li>
                                <li class="nav-item"> <a href="../almoxerifado/ordem-servico.php" class="nav-link"> Ordem de Serviço </a> </li>
                                <li class="nav-item"> <a href="../fornecedores/fornecedores.php" class="nav-link"> Fornecedores </a> </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="item">
                        <a href="../sair.php">
                            <img src="../assets/images/menu/sair.png" alt="">
                        </a>
                    </div>
                </div>                
            </div>
            <!-- finalizando menu lateral -->
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                    <div class="icone-menu-superior">
                        <img src="../assets/images/icones/reparos.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Solicitações Realizadas</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <!-- iniciando filtro por veiculo -->
                    <div class="filtro">
                        <form action="" class="form-inline" method="post">
                            <div class="form-row">
                                <div class="form-group">
                                    <select name="veiculo_filtrado" id="" class="form-control">
                                        <option value=""></option>
                                        <?php
                                            $sql = $db->query("SELECT placarVeiculo FROM solicitacoes GROUP BY placarVeiculo ORDER BY placarVeiculo ASC");
                                            if($sql->rowCount()>0){
                                                $dados = $sql->fetchAll();
                                                foreach($dados as $dado){
                                                    echo "<option value='$dado[placarVeiculo]'>". $dado['placarVeiculo'] ."</option>";

                                                }
                                            }
                                        ?>
                                    </select>
                                    <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- fim filtro por veiculo -->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <tbody>
                                <?php

                                    if($tipoUsuario==3 || $tipoUsuario == 99){
                                        if(isset($_POST['filtro']) && empty($_POST['veiculo_filtrado'])==false){
                                            $veiculo_filtrado = filter_input(INPUT_POST, 'veiculo_filtrado');
                                            $filtro = $db->query("SELECT solicitacoes.*, categoria_peca.* from solicitacoes LEFT JOIN categoria_peca ON solicitacoes.categoria_idcategoria = categoria_peca.idcategoria WHERE placarVeiculo = '$veiculo_filtrado'");

                                            echo '<tr>';
                                            echo'    <th scope="col" class="text-center">ID</th>';
                                            echo'    <th scope="col" class="text-center">Data</th>';
                                            echo'    <th scope="col" class="text-center"> Serviço/Peça </th>';
                                            echo'    <th scope="col" class="text-center"> Categoria </th>';
                                            echo'    <th scope="col" class="text-center"> Placa </th>';
                                            echo'    <th scope="col" class="text-center"> Imagem </th>';
                                            echo'    <th scope="col" class="text-center"> Local Reparo </th>';
                                            echo '  <th scope="col" class="text-center">Situação</th>';
                                            echo '  <th scope="col" class="text-center">Observação</th>';
                                            echo '  <th scope="col" class="text-center">Ações</th>';
                                            echo '</tr>';
                                            
                                            $totalSolic = $filtro->rowCount();
                                            $qtdePagina = 8;
                                            $numPaginas = ceil($totalSolic/$qtdePagina);
                                            $pagInicial = ($qtdePagina*$pagina)-$qtdePagina;
                                            $resul = $db->query("SELECT solicitacoes.*, categoria_peca.* from solicitacoes LEFT JOIN categoria_peca ON solicitacoes.categoria_idcategoria = categoria_peca.idcategoria WHERE placarVeiculo='$veiculo_filtrado' LIMIT $pagInicial, $qtdePagina ");
                                            $totalSoli = $resul->rowCount();
                                            
                                            if($resul->rowCount()>0){
                                                $dados = $resul->fetchAll();
                                                
                                                foreach($dados as $dado){
                                                    $id = $dado['id'];
                                                    echo '<tr>';
                                                    echo '<td class="text-center">' . $id . '</td>';
                                                    echo '<td class="text-center">' . date("d/m/Y", strtotime($dado['dataAtual'])) . '</td>';
                                                    echo '<td class="text-center">' . $dado['servico'] . '</>';
                                                    echo '<td class="text-center">' . $dado['nome_categoria'] . '</>';
                                                    echo '<td class="text-center">' . $dado['placarVeiculo'] . '</td>';
                                                    $nomeImg = $dado['imagem'];
                                                    $linkImg = "uploads/$nomeImg";
                                                    if($nomeImg==""){
                                                        $anexo = "<td class='text-center'>Sem Anexo   </td>";
                                                    }else{
                                                        $anexo = "<td class='text-center'> <a href='$linkImg' target='_blank'> Anexo </a>  </td>";
                                                    }
                                                    echo $anexo;
                                                    echo '<td class="text-center">' . $dado['localReparo'] . '</td>';
                                                    echo '<td class="text-center">' .$dado['statusSolic'] .'</td>';
                                                    echo '<td class="text-center">' .$dado['obs'] .'</td>';
                                                    $statusSolic = $dado['statusSolic'];
                                                    echo "<td class='text-center'>";
                                                    
                                                    echo "
                                                    <div class='dropdown'>
                                                        <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                            Opções
                                                        </button>
                                                        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
                                                            if($statusSolic=="Aprovado"){
                                                                echo "<a class='dropdown-item' href='gerar-pdf.php?id=$id'> Imprimir </a> ";
                                                                
                                                            }
                                                        echo"<a class='dropdown-item' href='nova-peca.php?id=$id'>Adicionar Nova Peça/Serviço</a>
                                                        </div>
                                                    </div>";
                                                        
                                                        echo "  ";
                                                    echo "</td>";
                                                }
                                            }

                                        }else{

                                            $sql = $db->query("SELECT * FROM solicitacoes ORDER BY dataAtual DESC LIMIT 200");
                                            echo '<tr>';
                                            echo'    <th scope="col" class="text-center">ID</th>';
                                            echo'    <th scope="col" class="text-center">Data</th>';
                                            echo'    <th scope="col" class="text-center"> Serviço/Peça </th>';
                                            echo'    <th scope="col" class="text-center"> Categoria </th>';
                                            echo'    <th scope="col" class="text-center"> Placa </th>';
                                            echo'    <th scope="col" class="text-center"> Imagem </th>';
                                            echo'    <th scope="col" class="text-center"> Local Reparo </th>';
                                            echo '   <th scope="col" class="text-center">Situação</th>';
                                            echo '   <th scope="col" class="text-center">Observação</th>';
                                            echo '   <th scope="col" class="text-center">Ações</th>';
                                            echo '</tr>';
                                            $totalSolic = $sql->rowCount();
                                            $qtdePagina = 10;
                                            $numPaginas = ceil($totalSolic/$qtdePagina);
                                            $pagInicial = ($qtdePagina*$pagina)-$qtdePagina;
                                            $resul = $db->query("SELECT solicitacoes.*, categoria_peca.* from solicitacoes LEFT JOIN categoria_peca ON solicitacoes.categoria_idcategoria = categoria_peca.idcategoria ORDER BY dataAtual DESC LIMIT $pagInicial, $qtdePagina ");
                                            $totalSoli = $resul->rowCount();
                                            if($resul->rowCount() > 0){
                                                
                                                $dados = $resul->fetchAll();
                        
                                                foreach ($dados as $dado) {
                                                    $id = $dado['id'];
                                                    echo '<tr>';
                                                    echo '<td class="text-center">' . $id . '</td>';
                                                    echo '<td class="text-center">' . date("d/m/Y", strtotime($dado['dataAtual'])) . '</td>';
                                                    echo '<td class="text-center">' . $dado['servico'] . '</>';
                                                    echo '<td class="text-center">' . $dado['nome_categoria'] . '</>';
                                                    echo '<td class="text-center">' . $dado['placarVeiculo'] . '</td>';
                                                    $nomeImg = $dado['imagem'];
                                                    $linkImg = "uploads/$nomeImg";
                                                    if($nomeImg==""){
                                                        $anexo = "<td class='text-center'>Sem Anexo   </td>";
                                                    }else{
                                                        $anexo = "<td class='text-center'> <a href='$linkImg' target='_blank'> Anexo </a>  </td>";
                                                    }
                                                    echo $anexo;
                                                    echo '<td class="text-center">' . $dado['localReparo'] . '</td>';
                                                    echo '<td class="text-center">' .$dado['statusSolic'] .'</td>';
                                                    echo '<td class="text-center">' .$dado['obs'] .'</td>';
                                                    $statusSolic = $dado['statusSolic'];
                                                    echo "<td class='text-center'>";
                                                    
                                                    echo "
                                                    <div class='dropdown'>
                                                        <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                            Opções
                                                        </button>
                                                        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
                                                            if($statusSolic=="Aprovado"){
                                                                echo "<a class='dropdown-item' href='gerar-pdf.php?id=$id'> Imprimir </a> ";
                                                                
                                                            }
                                                        echo"<a class='dropdown-item' href='nova-peca.php?id=$id'>Adicionar Nova Peça/Serviço</a>
                                                        </div>
                                                    </div>";
                                                        
                                                        echo "  ";
                                                    echo "</td>";
                                                        
                                                    
                                                }
                                            }
                                        } 
                                    }elseif($tipoUsuario==4){
                                        // iniciando filtragem
                                        if(isset($_POST['filtro']) && empty($_POST['veiculo_filtrado'])==false ){
                                            $veiculo_filtrado = filter_input(INPUT_POST, 'veiculo_filtrado');
                                            $filtro = $db->query("SELECT * FROM solicitacoes LEFT JOIN usuarios ON solicitacoes.idSolic = usuarios.idusuarios WHERE solicitacoes.idSolic = usuarios.idusuarios AND placarVeiculo = '$veiculo_filtrado' ORDER BY dataAtual DESC");

                                            echo '<tr>';
                                            echo '   <th scope="col" class="text-center text-nowrap">ID</th>';
                                            echo'    <th scope="col" class="text-center text-nowrap">Data</th>';
                                            echo'    <th scope="col" class="text-center text-nowrap"> Serviço/Peça </th>';
                                            echo'    <th scope="col" class="text-center text-nowrap"> Decrição do Problema </th>';
                                            echo'    <th scope="col" class="text-center text-nowrap"> Placa </th>';
                                            echo'    <th scope="col" class="text-center text-nowrap"> Imagem </th>';
                                            echo    '<th scope="col" class="text-center text-nowrap">Local de Reparo</th>';
                                            echo    '<th scope="col" class="text-center text-nowrap">Observação</th>';
                                            echo    '<th scope="col" class="text-center text-nowrap">Status</th>';
                                            echo    '<th scope="col" class="text-center text-nowrap">Ações</th>';
                                            echo '</tr>';

                                            $totalSolic = $filtro->rowCount();
                                            $qtdePagina = 30;
                                            $numPaginas = ceil($totalSolic/$qtdePagina);
                                            $pagInicial = ($qtdePagina*$pagina)-$qtdePagina;
                                            $resul = $db->query("SELECT * FROM solicitacoes LEFT JOIN usuarios ON solicitacoes.idSolic = usuarios.idusuarios WHERE placarVeiculo = '$veiculo_filtrado' ORDER BY dataAtual DESC LIMIT $pagInicial, $qtdePagina");
                                            $totalSoli = $resul->rowCount();
                                            
                                            if($resul->rowCount()>0){
                                                $dados = $resul->fetchAll();
                                                foreach($dados as $dado){
                                                    $id = $dado['id'];
                                                    echo '<tr>';
                                                    echo '<td>' .$id. '</td>';
                                                    echo '<td>' .date("d/m/Y", strtotime($dado['dataAtual'])). '</td>';
                                                    echo '<td>' .$dado['servico']. '</td>';
                                                    echo '<td>'.$dado['descricao'].'</td>';
                                                    echo '<td>' .$dado['placarVeiculo']. '</td>';
                                                    $nomeImg = $dado['imagem'];
                                                    $linkImg = "uploads/$nomeImg";
                                                    if($nomeImg==""){
                                                        $anexo = "<td class='text-center'>Sem Anexo   </td>";
                                                    }else{
                                                        $anexo = "<td class='text-center'> <a href='$linkImg' target='_blank'> Anexo </a>  </td>";
                                                    }
                                                    echo $anexo;
                                                    echo '<td>' .$dado['localReparo']. '</td>';
                                                    echo '<td>' .$dado['obs']. '</td>';
                                                    $status = $dado['statusSolic'];
                                                    echo '<td>'.$status.'</td>';
                                                    echo "<td>  
                                                            
                                                            <div class='dropdown'>
                                                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                                Opções
                                                                </button>
                                                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
                                                                    if($status !="Aprovado"){
                                                                        echo "<a class='dropdown-item' href='analisar.php?idSolic=$id'> Analisar </a>";
                                                                    }
                                                                    
                                                                    echo "<a class='dropdown-item' href='gerar-pdf.php?id=$id'> Imprimir </a>
                                                                    <a href='editar.php?id=$id' class='dropdown-item'> Excluir </a>
                                                                </div>
                                                                </div>
                                                        </td>";
                                                }
                                            }
                                        }else{
                                            
                                        
                                            $sql = $db->query("SELECT * FROM `solicitacoes` LEFT JOIN usuarios ON solicitacoes.idSolic = usuarios.idUsuarios ORDER BY dataAtual DESC LIMIT 200");
                                            echo '<tr>';
                                            echo '   <th scope="col" class="text-center text-nowrap">ID</th>';
                                            echo'    <th scope="col" class="text-center text-nowrap">Data</th>';
                                            echo'    <th scope="col" class="text-center text-nowrap"> Serviço/Peça </th>';
                                            echo'    <th scope="col" class="text-center text-nowrap"> Decrição do Problema </th>';
                                            echo'    <th scope="col" class="text-center text-nowrap"> Placa </th>';
                                            echo'    <th scope="col" class="text-center text-nowrap"> Imagem </th>';
                                            echo    '<th scope="col" class="text-center text-nowrap">Local de Reparo</th>';
                                            echo    '<th scope="col" class="text-center text-nowrap">Observação</th>';
                                            echo    '<th scope="col" class="text-center text-nowrap">Status</th>';
                                            echo    '<th scope="col" class="text-center text-nowrap">Ações</th>';
                                            echo '</tr>';
                                            $totalSolic = $sql->rowCount();
                                            $qtdePagina = 8;
                                            $numPaginas = ceil($totalSolic/$qtdePagina);
                                            $pagInicial = ($qtdePagina*$pagina)-$qtdePagina;
                                            $resul = $db->query("SELECT * FROM `solicitacoes` LEFT JOIN usuarios ON solicitacoes.idSolic = usuarios.idUsuarios ORDER BY dataAtual DESC LIMIT $pagInicial, $qtdePagina");
                                            $totalSoli = $resul->rowCount();
                                            if($resul->rowCount()>0){
                                                                            
                                                $dados = $resul->fetchAll();
                                                foreach($dados as $dado){
                                                    $id = $dado['id'];
                                                    echo '<tr>';
                                                    echo '<td>' .$id. '</td>';
                                                    echo '<td>' .date("d/m/Y", strtotime($dado['dataAtual'])). '</td>';
                                                    echo '<td>' .$dado['servico']. '</td>';
                                                    echo '<td>'.$dado['descricao'].'</td>';
                                                    echo '<td>' .$dado['placarVeiculo']. '</td>';
                                                    $nomeImg = $dado['imagem'];
                                                    $linkImg = "uploads/$nomeImg";
                                                    if($nomeImg==""){
                                                        $anexo = "<td class='text-center'>Sem Anexo   </td>";
                                                    }else{
                                                        $anexo = "<td class='text-center'> <a href='$linkImg' target='_blank'> Anexo </a>  </td>";
                                                    }
                                                    echo $anexo;
                                                    echo '<td>' .$dado['localReparo']. '</td>';
                                                    echo '<td>' .$dado['obs']. '</td>';
                                                    $status = $dado['statusSolic'];
                                                    echo '<td>'.$status.'</td>';
                                                    echo "<td>  
                                                            
                                                            <div class='dropdown'>
                                                                <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                                Opções
                                                                </button>
                                                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
                                                                    if($status !="Aprovado"){
                                                                        echo "<a class='dropdown-item' href='analisar.php?idSolic=$id'> Analisar </a>";
                                                                    }
                                                                    
                                                                    echo "<a class='dropdown-item' href='gerar-pdf.php?id=$id'> Imprimir </a>
                                                                    <a href='editar.php?id=$id' class='dropdown-item'> Excluir </a>
                                                                </div>
                                                                </div>
                                                        </td>";
                                                }

                                            }
                                        }
                                    }

                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
            
                        $paginaAnterior = $pagina-1;
                        $paginaPosterior = $pagina+1;
                                    
                    ?>
                    <nav aria-label="Navegação de página exemplo" class="paginacao">
                        <ul class="pagination">
                            <li class="page-item">
                            <?php
                                if($paginaAnterior!=0){
                                    echo "<a class='page-link' href='solicitacoes.php?pagina=$paginaAnterior' aria-label='Anterior'>
                                    <span aria-hidden='true'>&laquo;</span>
                                    <span class='sr-only'>Anterior</span>
                                </a>";
                                }else{
                                    echo "<a class='page-link' aria-label='Anterior'> 
                                        <span aria-hidden='true'>&laquo;</span>
                                        <span class='sr-only'>Anterior</span>
                                    </a>";
                                }
                            ?>
                            
                            </li>
                            <?php
                                for($i=1;$i < $numPaginas+1;$i++){
                                    echo "<li class='page-item'><a class='page-link' href='solicitacoes.php?pagina=$i'>$i</a></li>";
                                }
                            ?>
                            <li class="page-item">
                            <?php
                                if($paginaPosterior <= $numPaginas){
                                    echo " <a class='page-link' href='solicitacoes.php?pagina=$paginaPosterior' aria-label='Próximo'>
                                    <span aria-hidden='true'>&raquo;</span>
                                    <span class='sr-only'>Próximo</span>
                                </a>";
                                }else{
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