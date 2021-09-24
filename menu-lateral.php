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
            <a onclick="menuOcorrencias()">
                <img src="../assets/images/menu/ocorrencias.png" alt="">
            </a>
            <nav id="submenuOcorrencias">
                <ul class="nav flex-column">
                    <li class="nav-item"> <a class="nav-link" href="../ocorrencias/form-ocorrencias.php"> Registrar Nova Ocorrência </a> </li>
                    <li class="nav-item"> <a class="nav-link" href="../ocorrencias/ocorrencias.php"> Listar Ocorrências </a> </li>
                    <li class="nav-item"> <a class="nav-link" href="../ocorrencias/relatorio.php"> Ocorrências por Motorista</a> </li>
                    
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
                    <li class="nav-item"> <a onclick="menuEntregas()"> Entregas Capital </a>
                        <nav id="submenuCapital">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a href="../controle-despesas/entregas-capital/form-entregas.php"> Registrar Entregas </a> </li>
                                <li class="nav-item"> <a href="../controle-despesas/entregas-capital/entregas.php">  Entregas </a> </li>
                            </ul> 
                        </nav>
                    </li>
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
                    <li class="nav-item"> <a class="nav-link" href="../reparos/local-reparo.php">Local de Reparo</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="../reparos/pecas.php">Peças/Serviços</a> </li>
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
            <a onclick="menuPneu()">
                <img src="../assets/images/menu/pneu.png" alt="">
            </a>
            <nav id="submenuPneu">
                <ul class="nav flex-column">
                    <li class="nav-item ">  <a class="subtitulo" onclick="menuManutencao()"> Manutenções </a> 
                        <nav id="submenuManutencao">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a href="../pneus/manutencao/form-manutencao.php"> Registrar Manutenção </a> </li>
                                <li class="nav-item"> <a href="../pneus/manutencao/manutencoes.php">  Manutenções Realizadas </a> </li>
                            </ul> 
                        </nav>
                    </li>
                    <li class="nav-item ">  <a class="subtitulo" onclick="menuRodizio()"> Rodízios </a> 
                        <nav id="submenuRodizio">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a href="../pneus/rodizio/form-rodizio.php"> Realizar Rodízio </a> </li>
                                <li class="nav-item"> <a href="../pneus/rodizio/rodizio.php">  Rodízios Realizadas </a> </li>
                            </ul> 
                        </nav>
                    </li>
                    <li class="nav-item "> <a class="subtitulo" onclick="menuSuco()"> Medição de Suco </a>   
                        <nav id="submenuSuco">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a href="../pneus/suco/form-suco.php"> Medir Suco </a> </li>
                                <li class="nav-item"> <a href="../pneus/suco/sucos.php"> Medidas de Suco </a> </li>
                            </ul> 
                        </nav>
                    </li>
                    <li class="nav-item"> <a href="../pneus/form-pneus.php" class="nav-link"> Cadastrar Pneu </a> </li>
                    <li class="nav-item"> <a href="../pneus/pneus.php" class="nav-link"> Pneu Cadastrados </a> </li>
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