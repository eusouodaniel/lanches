/*
 * Códigos para o carregamento ajax de cidades, estados e paises.
 * @date 2013-05-15
 * @author Hugo Magalhães
 */

$(document).ready(function() {
    // Carrega os componentes presentes na página
    $(".ajax-location").each(function() {
        initAjaxLocation($(this), $(this).data('selected-district'), $(this).data('selected-city'), $(this).data('selected-state'));
    });
});

/**
 * Método para iniciar o componente de carregamento de cidades, estados e paises via ajax.
 * @param ajaxLocation Objeto DOM do container do componente.
 * @param districtId Id do bairro selecionado. Null caso não seja edição.
 * @param cityId Id da cidade selecionada. Null caso não seja edição.
 * @param stateId Id do estado selecionado. Null caso não seja edição.
 */
function initAjaxLocation(ajaxLocation, districtId, cityId, stateId) {
    var isEditing = (districtId && districtId != "");
    var districtDropdown = ajaxLocation.find('.ajax-location-district');
    var cityDropdown = ajaxLocation.find('.ajax-location-city');
    var stateDropdown = ajaxLocation.find('.ajax-location-state');

    // Verifica se a bairro está sendo editado
    if (isEditing) {
      // Carrega os estados e marca o selecionado
      loadStates(stateDropdown, function() {
          stateDropdown.val(stateId);
          // Carrega as cidades e marca a cidade selecionada
          loadCities(cityDropdown, stateId, function() {
              cityDropdown.val(cityId);

              loadDistricts(districtDropdown, cityId, function(){
                districtDropdown.val(districtId);
              });
          });
      });

    } else {
        loadStates(stateDropdown, function() {});
    }

    // Carrega as cidades ao alterar os estados
    stateDropdown.change(function(){
      if($(this).val()!= "" && $(this).val() != null){
        loadCities(cityDropdown, $(this).val(), function(){});
      }else{
        cityDropdown.find("option").remove();
        districtDropdown.find("option").remove();
      }
    });

    // Carrega os bairros ao alterar as cidades
    cityDropdown.change(function(){
      if($(this).val()!= "" && $(this).val() != null){
        loadDistricts(districtDropdown, $(this).val(), function(){});
      }else{
        districtDropdown.find("option").remove();
      }
    });

    $(".cep").blur(function(){
      /**
      * Resgatamos o valor do campo #cep
      * usamos o replace, pra remover tudo que não for numérico,
      * com uma expressão regular
      */
      var cep     = $(this).val().replace(/[^0-9]/, '');

      //Verifica se não está vazio
      if(cep !== ""){
        //Cria variável com a URL da consulta, passando o CEP
        var url = 'http://cep.correiocontrol.com.br/'+cep+'.json';

        /**
        * Fazemos um requisição a URL, como vamos retornar json,
        * usamos o método $.getJSON;
        * Que é composta pela URL, e a função que vai retorna os dados
        * o qual passamos a variável json, para recuperar os valores
        */
        $.getJSON(url, function(json){
          //Atribuimos o valor aos inputs
          $(".endereco").val(json.logradouro);
          stateDropdown.val(json.uf);
          //Carrega as cidades a partir do cep informado
          loadCities(cityDropdown, stateDropdown.val(), function() {
            //Verifica qual a cidade a partir do retorno da api
            var cityOptions = cityDropdown.find("option");
            cityOptions.filter(function() {
              return $(this).text() == json.localidade;
            }).attr('selected', true);
            //Carrega os bairros a partir do cep informado
            loadDistricts(districtDropdown, cityDropdown.val(), function(){
              //Verifica qual o bairro a partir do retorno da api
              var districtOptions = districtDropdown.find("option");
              districtOptions.filter(function() {
                return $(this).text() == json.bairro;
              }).attr('selected', true);
            });
          });
        });
      }
    });
};

/**
 * Método para carregar os estados via ajax no componente definido.
 * @param stateDropdown Objeto DOM onde os estados serão carregados.
 * @param callback Função que será executada após o carregamento.
 */
function loadStates(stateDropdown, callback){
    var routeUrl = Routing.generate('state_list_ajax');
    $.post(routeUrl, {},
        function(data){
            if(data.responseCode == 200) {
                stateDropdown.find("option").remove();
                $.each(data.results, function(i, value) {
                    stateDropdown.append( $('<option>').text(value.name).attr('value', value.acronym).data("item", value));
                });
                stateDropdown.prepend("<option></option>");
                stateDropdown.removeAttr('disabled');
                callback();
            }
    });
}

/**
 * Método para carregar as cidades via ajax no componente definido.
 * @param cityDropdown Objeto DOM onde as cidades serão carregadas.
 * @param state Id do estado do qual as cidades serão carregados.
 * @param callback Função que será executada após o carregamento.
 */
function loadCities(cityDropdown, state, callback){
    var routeUrl = Routing.generate('city_list_ajax');
    $.post(routeUrl, { "state": state },
        function(data){
            if(data.responseCode == 200) {
                cityDropdown.html("");
                $.each(data.results, function(i, value) {
                    cityDropdown.append( $('<option>').text(value.name).attr('value', value.id).data("item", value));
                });
                cityDropdown.prepend("<option></option>");
                cityDropdown.removeAttr('disabled');
                callback();
            }
    });
}

/**
 * Método para carregar os bairros via ajax no componente definido.
 * @param districtDropdown Objeto DOM onde os bairros serão carregadas.
 * @param city Id do estado do qual as cidades serão carregados.
 * @param callback Função que será executada após o carregamento.
 */
function loadDistricts(districtDropdown, city, callback){
    var routeUrl = Routing.generate('district_clientlist_ajax');
    $.post(routeUrl, { "city": city },
        function(data){
            if(data.responseCode == 200) {
                districtDropdown.html("");
                $.each(data.results, function(i, value) {
                    districtDropdown.append( $('<option>').text(value.name).attr('value', value.id).data("item", value));
                });
                districtDropdown.prepend("<option></option>");
                districtDropdown.removeAttr('disabled');

                callback();
            }
    });
}
