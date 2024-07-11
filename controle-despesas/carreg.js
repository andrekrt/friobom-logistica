$(document).ready(function(){
    $("input[name='nCarregamento']").blur(function(){
        var $vlTransp = $("input[name='vlTransp']");
        var $codVeiculo = $("input[name='codVeiculo']");
        var $codMotorista = $("input[name='codMotorista']");
        var $codRota = $("input[name='codRota']");
        var $pesoCarga = $("input[name='pesoCarga']");
        var $kmSaida = $("input[name='kmSaida']");
        var $qtdEntrega = $("input[name='qtdEntrega']");
        var $kmAbastecimento = $("input[name='km4Abast']");
        var $litrosAbastecido = $("input[name='lt4Abast']");
        var $valorAbastecido = $("input[name='vl4Abast']");
        var $localAbastecimento = $("input[name='local4Abast']");
        var $botao = $("button[type='submit']");
        var $doca = $("input[name='doca']");
        var nCarregamento = $(this).val();

        $.getJSON('num-carreg.php', {nCarregamento},
            function(retorno){
                let valorTroca = parseFloat(retorno.valorTroca);

                // comentando para desativar temporariamente essa trava
                // if(retorno.qtdTrocas>0){
                //     alert("Existe Trocas não conferidas! ");
                //     $botao.remove();
                // }

                // desativado temporiamente
                // if(valorTroca>0){
                //     let valorFormat = valorTroca.toFixed(2);
                //     let valorNumerico = parseFloat(valorFormat);
                //    valorFormat = valorNumerico.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                
                //     alert("Faltou itens que deu um total de "+ valorFormat + " informe quem é o responsavel");
                //     $("#linha").append(`
                //     <div class="form-group col-md-6 espaco">
                //         <label for="troca">Pagar Troca?</label>
                //         <select class="form-control" id="troca" name="troca" required>
                //             <option></option>
                //             <option>Vendedor</option>
                //             <option> Motorista </option>
                //             <option> Supervisor </option>
                //         </select>
                //     </div>
                //     `)
                   

                // }
                
               if(retorno.denegadas>0){
                    alert("Notas Denegas que ainda não foram resolvidas!");
                    $botao.remove();
               }else if(retorno.caixas>0){
                    alert("Existe caixas que ainda não foram acertadas");
                    $botao.remove();
               }else if(retorno.qtdCarregamentos>0){
                alert("Já existe despesa com o carregamento "+nCarregamento);
                $botao.remove();
               }
               else{
                    $vlTransp.val(retorno.vlTransp);
                    $codVeiculo.val(retorno.codVeiculo);
                    $codMotorista.val(retorno.codMotorista);
                    $codRota.val(retorno.codRota);
                    $pesoCarga.val(retorno.pesoCarga);
                    $kmSaida.val(retorno.kmSaida);
                    $qtdEntrega.val(retorno.qtdEntrega);
                    $kmAbastecimento.val(retorno.kmAbastecimento);
                    $litrosAbastecido.val(retorno.litrosAbastecimento);
                    $valorAbastecido.val(retorno.valorTotal);
                    $localAbastecimento.val(retorno.local);
                    $doca.val(retorno.doca);    
                }

                if(retorno.qtdVales>0){
                    alert("Existe vales não resgatado para o carregamento: "+nCarregamento);
                    $botao.remove();
                }
                
            }

            
        );
    });
});