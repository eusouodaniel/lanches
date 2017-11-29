$(document).ready(function () {
    $.get("http://developers.agenciaideias.com.br/cotacoes/json", function (data) {
        var cotacao = data.dolar.cotacao;
        var variacao = data.dolar.variacao;
        var att = data.atualizacao;
        $('.cotacao-valor').html(cotacao.replace(".", ","));
        $('.cotacao-variacao').html(variacao);
        $('.cotacao-atualizacao').html(att);
    });

    /*Weather.getCurrent("Kansas City", function(current) {
     console.log(
     ["currently:",current.temperature(),"and",current.conditions()].join(" ")
     );
     });*/
});