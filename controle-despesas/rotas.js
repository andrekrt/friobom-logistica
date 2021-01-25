$(document).ready(function(){
    $("input[name='codRota']").blur(function(){
        var $rota = $("input[name='rota']");
        var codRota = $(this).val();

        $.getJSON('pesquisaRota.php', {codRota},
            function(retorno){
                $rota.val(retorno.rota);
            }
        );
    });
});