<?php 

session_start();
require("../conexao.php");

$idModudulo = 10;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $idCheck = filter_input(INPUT_GET, 'id');
    $sql = $db->prepare("SELECT * FROM checklist_apps LEFT JOIN veiculos ON checklist_apps.veiculo = veiculos.cod_interno_veiculo WHERE id = :id");
    $sql->bindValue(":id", $idCheck);
    $sql->execute();
    $chekc = $sql->fetch();
    
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

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                        <h2>Check-List de Retorno</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="add-retorno.php" class="despesas" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?=$idCheck?>">
                        <div class="form-row"> 
                            <div class="form-group col-md-2 espaco">
                                <label for="veiculo">Veículo</label>
                                <input type="text" name="veiculo" id="veiculo" readonly class="form-control" value="<?=$chekc['veiculo']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="hrTk">Hora do Tk Retorno</label>
                                <input type="text" required name="hrTk" class="form-control" id="hrTk">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="carregamento">Carregamento</label>
                                <input type="text" required name="carregamento" class="form-control" id="carregamento">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="cabine">Limpesa de Cabine</label>
                                <select name="cabine" id="cabine" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="retrovisores">Retrovisores</label>
                                <select name="retrovisores" id="retrovisores" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="parabrisa">Limpador e Levador de Para-brisa</label>
                                <select name="parabrisa" id="parabrisa" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="quebasol">Quebra Sol</label>
                                <select name="quebasol" id="quebasol" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="bordo">Veloc./Tacog./Comp. de Bordo</label>
                                <select name="bordo" id="bordo" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="buzina">Buzina</label>
                                <select name="buzina" id="buzina" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="cinto">Cinto de Segurança</label>
                                <select name="cinto" id="cinto" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="extintor">Extintor de Incêndio</label>
                                <select name="extintor" id="extintor" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="triangulo">Triângulo de Sinalização</label>
                                <select name="triangulo" id="triangulo" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="macaco">Macaco e Chave de Roda</label>
                                <select name="macaco" id="macaco" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="tanque">Portas e Tampas Taque de Comb.</label>
                                <select name="tanque" id="tanque" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="janela">Vidros e Janelas</label>
                                <select name="janela" id="janela" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="banco">Forro do Banco</label>
                                <select name="banco" id="banco" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="porta">Maçaneta da Porta</label>
                                <select name="porta" id="porta" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="cambio">Alavanca do Câmbio</label>
                                <select name="cambio" id="cambio" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="seta">Lanternas Indicadoras de Direção</label>
                                <select name="seta" id="seta" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="luzFreio">Lanternas de Freio/Freio e Elevada</label>
                                <select name="luzFreio" id="luzFreio" class="form-control" required >
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="luzRe">Lanternas de Marcha Ré</label>
                                <select name="luzRe" id="luzRe" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="alerta">Pisca Alerta</label>
                                <select name="alerta" id="alerta" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4 espaco">
                                <label for="luzTeto">Luze de Sinalização Intermitente do Teto</label>
                                <select name="luzTeto" id="luzTeto" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="faixas">Faixas Refletivas/Retrorefletivas</label>
                                <select name="faixas" id="faixas" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="pneus">Estado Geral dos Pneus</label>
                                <select name="pneus" id="pneus" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="rodas">Estado Geral das Rodas</label>
                                <select name="rodas" id="rodas" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="estepe">Pneus de Estepe</label>
                                <select name="estepe" id="estepe" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="molas">Estado Geral das Molas</label>
                                <select name="molas" id="molas" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="cabo">Cabo de Força</label>
                                <select name="cabo" id="cabo" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="refrigeracao">Refrigeração</label>
                                <select name="refrigeracao" id="refrigeracao" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="ventilador">Ventiladores do Equipamento</label>
                                <select name="ventilador" id="ventilador" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="farolDianteiro">Farol Dianteiro</label>
                                <select name="farolDianteiro" id="farolDianteiro" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="farolTraseiro">Farol Traseiro</label>
                                <select name="farolTraseiro" id="farolTraseiro" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="farolNeblina">Faróis de Neblina</label>
                                <select name="farolNeblina" id="farolNeblina" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="farolAlto">Faróis de Longo Alcance(Alto)</label>
                                <select name="farolAlto" id="farolAlto" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="luzPainel">Luzes do Painel</label>
                                <select name="luzPainel" id="luzPainel" class="form-control" required>
                                    <option value=""></option>
                                    <option value="OK">OK</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 espaco">
                                <label for="obs">Observações</label>
                                <textarea name="obs" id="obs" rows="5" class="form-control"></textarea>
                            </div>                           
                        </div>
                        <button type="submit" class="btn btn-primary"> Registrar </button>
                    </form>
                </div>
            </div>
        </div>
 
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script src="../assets/js/jquery.mask.js"></script>
        <script>
             jQuery(function($){
                $("#vlCarga").mask('###0,00', {reverse: true});
                $("#peso").mask('###0,00', {reverse: true});
                $("#ltAbastecido").mask('###0,00', {reverse: true});
                $("#vlAbastecido").mask('###0,00', {reverse: true});
            });

            $(document).ready(function() {
                $('#motorista').select2();
                $('#rota').select2();
            });
        </script>
    </body>
</html>