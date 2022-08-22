<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && ($_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario']==99)){

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
        <title>Lançar Nova Despesa</title>
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
                        <img src="../assets/images/icones/icone-checklist.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Check-List</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="add-check.php" class="despesas" method="post" enctype="multipart/form-data">
                        <div class="form-row"> 
                            <div class="form-group col-md-2 espaco">
                                <label for="veiculo">Veículo</label>
                                <select name="veiculo" id="veiculo" required class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $sql = $db->query("SELECT * FROM veiculos WHERE ativo = 1 ORDER BY placa_veiculo ASC");
                                    if ($sql->rowCount() > 0) {
                                        $dados = $sql->fetchAll();
                                        foreach ($dados as $dado) {
                                            echo "<option value='$dado[cod_interno_veiculo]'>" . $dado['placa_veiculo'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="saida">Data Saída</label>
                                <input type="date" required name="saida" class="form-control" id="saida">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="cabine">Limpesa de Cabine</label>
                                <input type="text" required name="cabine" class="form-control"  list="cabine">
                                <datalist id="cabine">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="retrovisores">Retrovisores</label>
                                <input type="text" required name="retrovisores" class="form-control"  list="retrovisores">
                                <datalist id="retrovisores">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="parabrisa">Limpador e Levador de Para-brisa</label>
                                <input type="text" required name="parabrisa" class="form-control"  list="parabrisa">
                                <datalist id="parabrisa">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="quebasol">Quebra Sol</label>
                                <input type="text" required name="quebasol" class="form-control"  list="quebasol">
                                <datalist id="quebasol">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="bordo">Veloc./Tacog./Comp. de Bordo</label>
                                <input type="text" required name="bordo" class="form-control"  list="bordo">
                                <datalist id="bordo">
                                    <option value="OK">
                                </datalist>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="buzina">Buzina</label>
                                <input type="text" required name="buzina" class="form-control"  list="buzina">
                                <datalist id="buzina">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="cinto">Cinto de Segurança</label>
                                <input type="text" required name="cinto" class="form-control"  list="buzina">
                                <datalist id="buzina">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="extintor">Extintor de Incêndio</label>
                                <input type="text" required name="extintor" class="form-control"  list="extintor">
                                <datalist id="extintor">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="triangulo">Triângulo de Sinalização</label>
                                <input type="text" required name="triangulo" class="form-control"  list="triangulo">
                                <datalist id="triangulo">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="macaco">Macaco e Chave de Roda</label>
                                <input type="text" required name="macaco" class="form-control"  list="macaco">
                                <datalist id="macaco">
                                    <option value="OK">
                                </datalist>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="tanque">Portas e Tampas Taque de Comb.</label>
                                <input type="text" required name="tanque" class="form-control"  list="tanque">
                                <datalist id="tanque">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="janela">Vidros e Janelas</label>
                                <input type="text" required name="janela" class="form-control"  list="janela">
                                <datalist id="janela">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="banco">Forro do Banco</label>
                                <input type="text" required name="banco" class="form-control"  list="banco">
                                <datalist id="banco">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="porta">Maçaneta da Porta</label>
                                <input type="text" required name="porta" class="form-control"  list="porta">
                                <datalist id="porta">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="cambio">Alavanca do Câmbio</label>
                                <input type="text" required name="cambio" class="form-control"  list="cambio">
                                <datalist id="cambio">
                                    <option value="OK">
                                </datalist>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="seta">Lanternas Indicadoras de Direção</label>
                                <input type="text" required name="seta" class="form-control"  list="seta">
                                <datalist id="seta">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="luzFreio">Lanternas de Freio/Freio e Elevada</label>
                                <input type="text" required name="luzFreio" class="form-control"  list="luzFreio">
                                <datalist id="luzFreio">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="luzRe">Lanternas de Marcha Ré</label>
                                <input type="text" required name="luzRe" class="form-control"  list="luzRe">
                                <datalist id="luzRe">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="alerta">Pisca Alerta</label>
                                <input type="text" required name="alerta" class="form-control"  list="alerta">
                                <datalist id="alerta">
                                    <option value="OK">
                                </datalist>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4 espaco">
                                <label for="luzTeto">Luze de Sinalização Intermitente do Teto</label>
                                <input type="text" required name="luzTeto" class="form-control"  list="luzTeto">
                                <datalist id="luzTeto">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="faixas">Faixas Refletivas/Retrorefletivas</label>
                                <input type="text" required name="faixas" class="form-control"  list="faixas">
                                <datalist id="faixas">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="pneus">Estado Geral dos Pneus</label>
                                <input type="text" required name="pneus" class="form-control"  list="pneus">
                                <datalist id="pneus">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="rodas">Estado Geral das Rodas</label>
                                <input type="text" required name="rodas" class="form-control"  list="rodas">
                                <datalist id="rodas">
                                    <option value="OK">
                                </datalist>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="estepe">Pneus de Estepe</label>
                                <input type="text" required name="estepe" class="form-control"  list="estepe">
                                <datalist id="estepe">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="molas">Estado Geral das Molas</label>
                                <input type="text" required name="molas" class="form-control"  list="molas">
                                <datalist id="molas">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="cabo">Cabo de Força</label>
                                <input type="text" required name="cabo" class="form-control"  list="cabo">
                                <datalist id="cabo">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="refrigeracao">Refrigeração</label>
                                <input type="text" required name="refrigeracao" class="form-control"  list="refrigeracao">
                                <datalist id="refrigeracao">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="ventilador">Ventiladores do Equipamento</label>
                                <input type="text" required name="ventilador" class="form-control"  list="ventilador">
                                <datalist id="ventilador">
                                    <option value="OK">
                                </datalist>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="farolDianteiro">Farol Dianteiro</label>
                                <input type="text" required name="farolDianteiro" class="form-control"  list="farolDianteiro">
                                <datalist id="farolDianteiro">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="farolTraseiro">Farol Traseiro</label>
                                <input type="text" required name="farolTraseiro" class="form-control"  list="farolTraseiro">
                                <datalist id="farolTraseiro">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="farolNeblina">Faróis de Neblina</label>
                                <input type="text" required name="farolNeblina" class="form-control"  list="farolNeblina">
                                <datalist id="farolNeblina">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="farolAlto">Faróis de Longo Alcance(Alto)</label>
                                <input type="text" required name="farolAlto" class="form-control"  list="farolAlto">
                                <datalist id="farolAlto">
                                    <option value="OK">
                                </datalist>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="luzPainel">Luzes do Painel</label>
                                <input type="text" required name="luzPainel" class="form-control"  list="luzPainel">
                                <datalist id="luzPainel">
                                    <option value="OK">
                                </datalist>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group mb-3 espaco">
                                <label for="fotos" class="form-label ">Fotos do Veículo(Anexe Fotos já tiradas anteriormente)</label>
                                <input class="form-control" required type="file" id="fotos" name="fotos[]" multiple>
                                </div>
                            </div>
                        <button type="submit" class="btn btn-primary"> Registrar </button>
                    </form>
                </div>
            </div>
        </div>
        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
    </body>
</html>