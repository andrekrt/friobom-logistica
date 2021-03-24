<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==2 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    
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
        <title>Realizar Check-List</title>
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
                                <li class="nav-item"> <a class="nav-link" href="../controle-despesas/despesas.php"> Despesas </a> </li>
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
                        <img src="../assets/images/icones/check.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Novo Check-List</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <div class="form-check">
                        <form action="add-check.php" method="post">
                        
                                <div class="form-group espaco">
                                    <label for="placa">Placa do Veículo</label>
                                    <input type="text"  required name="placa" class="form-control" id="placa">
                                </div>
                                <div class="form-group espaco">
                                    <label for="tipoVeiculo">Tipo do Veículo</label>
                                    <input type="text" required name="tipoVeiculo" class="form-control" id="tipoVeiculo">
                                </div>                    
                                <div class="form-group espaco" >
                                    <label for="limpeza">Limpeza</label>
                                    <select name="limpeza" id="limpeza" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="retrovisores">Retrovisores</label>
                                    <select name="retrovisores" id="retrovisores" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="paraBrisas">Para Brisas</label>
                                    <select name="paraBrisas" id="paraBrisas" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="quebraSol">Quebra Sol</label>
                                    <select name="quebraSol" id="quebraSol" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="pcBorddo">Computador Bordo</label>
                                    <select name="pcBordo" id="pcBorddo" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="buzina">Buzina</label>
                                    <select name="buzina" id="buzina" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                            
                                <div class="form-group espaco" >
                                    <label for="cinto">Cinto de Segurança</label>
                                    <select name="cinto" id="cinto" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="extintor">Extintor</label>
                                    <select name="extintor" id="extintor" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="triangulo">Triângulo de Sinalização</label>
                                    <select name="triangulo" id="triangulo" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="macaco">Macaco e Chave de Roda</label>
                                    <select name="macaco" id="macaco" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="tanqueCombustivel">Tanque de Combustível</label>
                                    <select name="tanqueCombustivel" id="tanqueCombustivel" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="janelas">Vidros e Janelas</label>
                                    <select name="janelas" id="janelas" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="luzDirecao">Luzes de Direção</label>
                                    <select name="luzDirecao" id="luzDirecao" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="luzFreio">Luz de Freio e Elevada</label>
                                    <select name="luzFreio" id="luzFreio" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="luzRe">Luz de Marcha Ré</label>
                                    <select name="luzRe" id="luzRe" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="piscaAlerta">Pisca Alerta</label>
                                    <select name="piscaAlerta" id="piscaAlerta" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="luzTeto">Luzes do Teto</label>
                                    <select name="luzTeto" id="luzTeto" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="faixaRefletiva">Faixas Refletivas</label>
                                    <select name="faixaRefletiva" id="faixaRefletiva" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                            
                                <div class="form-group espaco" >
                                    <label for="farolDianteiro">Farol Dianteiro</label>
                                    <select name="farolDianteiro" id="farolDianteiro" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="farolTraseiro">Farol Traseiro</label>
                                    <select name="farolTraseiro" id="farolTraseiro" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="farolNeblina">Faróis de Neblina</label>
                                    <select name="farolNeblina" id="farolNeblina" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="farolAlto">Faróis Alto</label>
                                    <select name="farolAlto" id="farolAlto" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="luzPainel">Luzes do Painel</label>
                                    <select name="luzPainel" id="luzPainel" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="pneus">Estado dos Pneus</label>
                                    <select name="pneus" id="pneus" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="rodas">Estado das Rodas</label>
                                    <select name="rodas" id="rodas" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="estepe">Pneus de Estepe</label>
                                    <select name="estepe" id="estepe" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="molas">Estados das Molas</label>
                                    <select name="molas" id="molas" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco" >
                                    <label for="caboForca">Cabo de Força</label>
                                    <select name="caboForca" id="caboForca" class="form-control">
                                        <option value="OK">OK</option>
                                        <option value="NÃO">NÃO</option>
                                    </select>
                                </div>
                                <div class="form-group espaco">
                                    <label for="">Descreva todos os problemas encontrado.</label>
                                    <textarea name="observacoes" id="" rows="3" class="form-control"></textarea>
                                </div>
                            <button class="btn btn-primary"> Enviar </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="veiculo.js"></script>
    </body>
</html>