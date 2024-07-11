<?php 

session_start();
require("../conexao.php");

$idModudulo = 7;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

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
                        <img src="../assets/images/icones/despesas.png" alt="">
                   </div>
                   <div class="title">
                        <h2>Lançar Nova Despesa</h2>
                   </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="add-despesas.php" class="despesas" method="post" enctype="multipart/form-data">
                        <div class="form-row"> 
                            <div class="form-group col-md-3 espaco">
                                <label for="nCarregamento">Nº Carregamento</label>
                                <input type="text" required name="nCarregamento" class="form-control" id="nCarregamento">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="codVeiculo">Código do Veículo</label>
                                <input type="text" required name="codVeiculo" class="form-control" id="codVeiculo">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="tipoVeiculo">Tipo do Veículo</label>
                                <input type="text" required name="tipoVeiculo" class="form-control" id="tipoVeiculo">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="placaVeiculo">Placa do Veículo</label>
                                <input type="text" required name="placaVeiculo" class="form-control" id="placaVeiculo">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="codMotorista">Código do Motorista</label>
                                <input type="text" required name="codMotorista" class="form-control" id="codMotorista">
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="nomeMotorista">Nome do Motorista</label>
                                <input type="text" required name="motorista" class="form-control" id="nomeMotorista">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="dataCarregamento">Data do Carregamento</label>
                                <input type="DateTime-Local" required name="dataCarregamento" class="form-control" id="dataCarregamento">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="dataSaida">Data e hora de Saída</label>
                                <input type="DateTime-Local" required name="dataSaida" class="form-control" id="dataSaida">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="dataChegada">Data e Hora de Chegada</label>
                                <input type="DateTime-Local" required name="dataChegada" class="form-control" id="dataChegada">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="codRota">Código da Rota</label>
                                <input type="text" required name="codRota" class="form-control" id="codRota">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="rota">Rota</label>
                                <input type="text" required name="rota" class="form-control" id="rota">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="vlTransp">Valor Transportado (R$)</label>
                                <input type="text" required name="vlTransp" class="form-control" id="vlTransp">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="vlDev">Valor Devolvido (R$)</label>
                                <input type="text" required name="vlDev" class="form-control" id="vlDev">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="qtdEntrega">Qtde de Entregas</label>
                                <input type="text" required name="qtdEntrega" class="form-control" id="qtdEntrega">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="nCarga">Nº da Carga</label>
                                <input type="text" required name="nCarga" class="form-control" id="nCarga">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="pesoCarga">Peso da Carga</label>
                                <input type="text" required name="pesoCarga" class="form-control" id="pesoCarga">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="kmSaida">KM de Saída</label>
                                <input type="text" required name="kmSaida" class="form-control" id="kmSaida">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="hrTkSaida">HR TK Saída</label>
                                <input type="text" name="hrTkSaida" class="form-control" id="hrTkSaida">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="km1Abast">KM 1º Abast. Externo</label>
                                <input type="text" required name="km1Abast" class="form-control" id="km1Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="lt1Abast">Litros 1º Abast. Externo</label>
                                <input type="text" required name="lt1Abast" class="form-control" id="lt1Abast">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="vl1Abast">Valor 1º Abastescimento Externo</label>
                                <input type="text" name="vl1Abast" class="form-control" id="vl1Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="local1Abast">Posto / Cidade 1º Abastecimento Externo</label>
                                <input type="text" name="local1Abast" class="form-control" id="local1Abast">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="nf1Abast">Nº NF 1º Abastecimento</label>
                                <input type="text" name="nf1Abast" class="form-control" id="nf1Abast">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="km2Abast">KM 2º Abastescimento</label>
                                <input type="text" name="km2Abast" class="form-control" id="km2Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="lt2Abast">Litros 2º Abastescimento Externo</label>
                                <input type="text" name="lt2Abast" class="form-control" id="lt2Abast">
                            </div>
                            <div class="form-group col-md-32 espaco">
                                <label for="vl2Abast">Valor 2º Abastescimento Externo</label>
                                <input type="text" name="vl2Abast" class="form-control" id="vl2Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="local2Abast">Posto / Cidade 2º Abastecimento Externo</label>
                                <input type="text" name="local2Abast" class="form-control" id="local2Abast">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="nf2Abast">Nº NF 2º Abastecimento</label>
                                <input type="text" name="nf2Abast" class="form-control" id="nf2Abast">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="km3Abast">KM 3º Abast. Externo</label>
                                <input type="text" name="km3Abast" class="form-control" id="km3Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="lt3Abast">Litros 3º Abastescimento Externo</label>
                                <input type="text" name="lt3Abast" class="form-control" id="lt3Abast">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="vl3Abast">Valor 3º Abastescimento Externo</label>
                                <input type="text" name="vl3Abast" class="form-control" id="vl3Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="local3Abast">Posto / Cidade 3º Abastecimento Externo</label>
                                <input type="text" name="local3Abast" class="form-control" id="local3Abast">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="nf3Abast">Nº NF 3º Abastecimento</label>
                                <input type="text" name="nf3Abast" class="form-control" id="nf3Abast">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="km4Abast">KM Abast. Interno</label>
                                <input type="text" name="km4Abast" class="form-control" id="km4Abast">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="hrKm4Abast">HR TK Chegada</label>
                                <input type="text" name="hrKm4Abast" class="form-control" id="hrKm4Abast">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="lt4Abast">Litros Abast.  Interno</label>
                                <input type="text" name="lt4Abast" class="form-control" id="lt4Abast">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="vl4Abast">Valor Abast. Interno</label>
                                <input type="text" name="vl4Abast" class="form-control" id="vl4Abast">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="local4Abast">Posto / Cidade Abastecimento Interno</label>
                                <input type="text" required name="local4Abast" class="form-control" id="local4Abast">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="diariasMot">Valor Diária Motorista</label>
                                <input type="text" required name="diariasMot" class="form-control" id="diariasMot">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diasRota">Dias em Rota Motorista</label>
                                <input type="text" required name="diasRota" class="form-control" id="diasRota">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diariasAjud">Valor Diária Ajudante </label>
                                <input type="text" required name="diariasAjud" class="form-control" id="diariasAjud">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diasRotaAjud">Dias em Rota Ajudante</label>
                                <input type="text" required name="diasRotaAjud" class="form-control" id="diasRotaAjud">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diariasChapa">Valor Diária Chapa</label>
                                <input type="text" required name="diariasChapa" class="form-control" id="diariasChapa">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diasRotaChapa">Dias em Rota Chapa</label>
                                <input type="text" required name="diasRotaChapa" class="form-control" id="diasRotaChapa">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="gastosAjud">Outros Gastos</label>
                                <input type="text" name="gastosAjud" class="form-control" id="gastosAjud">
                            </div>
                        </div>
                        <div class="form-row">
                            <!-- Campo Tomada foi apenas trocado para campo almoço -->
                            <div class="form-group col-md-2 espaco">
                                <label for="tomada">Almoço</label>
                                <input type="text"  name="tomada" class="form-control" id="tomada">
                            </div>
                            <!-- Campo descarga foi trocado por Passagem -->
                            <div class="form-group col-md-2 espaco">
                                <label for="descarga">Passagem</label>
                                <input type="text" name="descarga" class="form-control" id="descarga">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="travessia">Travessia</label>
                                <input type="text" name="travessia" class="form-control" id="travessia">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="servicos">Serviços</label>
                                <input type="text" name="servicos" class="form-control" id="servicos">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="nomeAjud">Nome Ajudante</label>
                                <input type="text" name="nomeAjud" class="form-control" id="nomeAjud">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-goup col-md-4 espaco">
                                <label for="chapa1">Nome Chapa 1</label>
                                <input type="text" name="chapa1" class="form-control" id="chapa1">
                            </div>
                            <div class="form-goup col-md-4 espaco">
                                <label for="chapa2">Nome Chapa 2</label>
                                <input type="text" name="chapa2" class="form-control" id="chapa2">
                            </div>
                            <div class="mb-3 form-grupo col-md-4 espaco">
                                <label for="imagem" class="form-label">Imagem da carga</label>
                                <input type="file" name="imagem" class="form-control" id="imagem" >    
                            </div> 
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="arrumacao">A arrumação fez com que avariasse algum produto? </label>
                                <select name="arrumacao" required id="arrumacao" class="form-control">
                                    <option value=""></option>
                                    <option value="0">SIM</option>
                                    <option value="1">NÃO</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-4 espaco">
                                <label for="caixaErrada">Tinha produto dentro da caixa de outro produto que atrapalhou a entrega? </label>
                                <select name="caixaErrada" required id="caixaErrada" class="form-control">
                                    <option value=""></option>
                                    <option value="0">SIM</option>
                                    <option value="1">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-5 espaco">
                                <label for="produtoErrado">Tinha algum produto que foi colocado atrás de outro que ficou de difícil visão dentro do baú?</label>
                                <select name="produtoErrado" id="produtoErrado" required class="form-control">
                                    <option value=""></option>
                                    <option value="0">SIM</option>
                                    <option value="1">NÃO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="miudeza">Tinha miudeza solta no caminhão? </label>
                                <select name="miudeza" required id="miudeza" class="form-control">
                                    <option value=""></option>
                                    <option value="0">SIM</option>
                                    <option value="1">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 espaco" id="after">
                                <label for="voltouCliente">Teve que ir no cliente mais de 1 vez por conta de produtos não encontrados, mas que fora encontrados depois?</label>
                                <select name="voltouCliente" id="voltouCliente" required class="form-control">
                                    <option value=""></option>
                                    <option value="0">SIM</option>
                                    <option value="1">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="setorBaguncado">Qual depósito deu mais trabalho?</label>
                                <select name="setorBaguncado" required id="setorBaguncado" class="form-control">
                                    <option value=""></option>
                                    <option value="Seco">Seco</option>
                                    <option value="Frios">Frios</option>
                                    <option value="Ambos">Ambos</option>
                                    <option value="Nenhum">Nenhum</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 espaco">
                                <label for="obs">Fale Mais Sobre Sua Carga</label>
                                <textarea class="form-control" id="obs" rows="3" name="obs" required></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="doca" id="doca">
                        <button type="submit" class="btn btn-primary" name="cadastrar"> Cadastrar </button>
                    </form>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="personalizado.js"></script>
        <script type="text/javascript" src="motoristas.js"></script>
        <script type="text/javascript" src="rotas.js"></script>
        <script type="text/javascript" src="carreg.js"></script>
        <script src="../assets/js/jquery.mask.js"></script>
        <!-- sweert alert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            jQuery(function($){
                $("#vlTransp").mask('###0,00', {reverse: true});
                $("#vlDev").mask('###0,00', {reverse: true});
                $("#pesoCarga").mask('###0,00', {reverse: true});
                $("#lt1Abast").mask('###0,00', {reverse: true});
                $("#vl1Abast").mask('###0,00', {reverse: true});
                $("#lt2Abast").mask('###0,00', {reverse: true});
                $("#vl2Abast").mask('###0,00', {reverse: true});
                $("#lt3Abast").mask('###0,00', {reverse: true});
                $("#vl3Abast").mask('###0,00', {reverse: true});
                $("#lt4Abast").mask('###0,00', {reverse: true});
                $("#vl4Abast").mask('###0,00', {reverse: true});
                $("#diariasMot").mask('###0,00', {reverse: true});
                $("#diariasAjud").mask('###0,00', {reverse: true});
                $("#diariasChapa").mask('###0,00', {reverse: true});
                $("#gastosAjud").mask('###0,00', {reverse: true});
                $("#tomada").mask('###0,00', {reverse: true});
                $("#descarga").mask('###0,00', {reverse: true});
                $("#travessia").mask('###0,00', {reverse: true});
                $("#servicos").mask('###0,00', {reverse: true});
            });

            $(document).ready(function(){
                $('#hrTkSaida').change(function(){
                    var tkSaida = $('#hrTkSaida').val();
                    if(tkSaida<=0){
                        alert('Hora do Tk de Saída precisa ser Maior que Zero.');
                        $('#hrTkSaida').val("");
                    }
                    
                });
                $('#hrKm4Abast').blur(function(){
                    var tkSaida = parseInt($('#hrTkSaida').val(),10) ;
                    var tkRetorno =parseInt($('#hrKm4Abast').val(),10) ;
                    
                    if(tkRetorno<tkSaida){
                        alert('Hora do Tk de retorno precisa ser maior ou igual ao de saída');
                        $('#hrKm4Abast').val("");
                    }
                });
                $('#km4Abast').change(function(){
                    let kmSaida = parseInt($('#kmSaida').val(),10) ;
                    let kmRetorno =parseInt($('#km4Abast').val(),10) ;

                    if(kmRetorno<kmSaida){
                        alert("Km de Retorno precisa ser maior que o de saída.");
                        $('#km4Abast').val("");
                    }
                });

                $('#voltouCliente').change(function(){
                    let voltouCliente = $('#voltouCliente').val();
                    if(voltouCliente=="0"){
                        let inputExtra = `
                        <div class="form-group col-md-5 espaco" id="inputExtra">
                            <label for="qtdVezes">Em quantos clientes tever que retornar?</label>
                            <input class="form-control" type="number" id="qtdVezes" name="qtdVezes">
                        </div>
                        `
                        $(inputExtra).insertAfter('#after');
                    }else if(voltouCliente=="1" || ($('#inputExtra').length==1)){
                       $('#inputExtra').remove();
                    }
                });
            });
        </script>
        <!-- msg de sucesso ou erro -->
    <?php
    // Verifique se há uma mensagem de confirmação na sessão
    if (isset($_SESSION['msg']) && $_SESSION['icon']) {
        // Exiba um alerta SweetAlert
        echo "<script>
                Swal.fire({
                  icon: '$_SESSION[icon]',
                  title: '$_SESSION[msg]',
                  showConfirmButton: true,
                });
              </script>";

        // Limpe a mensagem de confirmação da sessão
        unset($_SESSION['msg']);
        unset($_SESSION['status']);
    }
    ?>
    </body>
</html>