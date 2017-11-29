$(document).ready(function() {

    // Aplica o select2 nos campos definidos
    var applySelect2 = function(){
      $(".select2").select2();
    }

    // Inicialização das tabelas
    initiateRegions();

    // Adiciona novo item ao horário
    $("#regions-add").click(function(e){
        e.preventDefault();
        addRegionsItem();
        applySelect2();
    });

    // Seta a função de exclusão nos itens da tabela de disponibilidade
    $("#regions-table tbody").children().each(function() {
        var regionItem = $(this).closest('tr.regions-item');
        removeRegionsItem(regionItem);

    });

    applySelect2();

});

/**
 * Função para inicializar o indice da tabela de disponibilidade
 */
function initiateRegions(){
    // Atualiza o indice do prototype com a quantidade de itens disponíveis
    $('#regions-table').attr('data-index-count', $(".regions-item").length);
}

/**
 * Método que adiciona um novo dia e horário de disponibilidade.
 */
function addRegionsItem() {
    var collectionHolder = $('#regions-table');
    var prototype = collectionHolder.attr('data-prototype');
    var count = collectionHolder.attr('data-index-count');
    var newForm = $(prototype.replace(/\_\_name\_\_/g, count));

    collectionHolder.find('tbody').append(newForm);
    collectionHolder.attr('data-index-count', ++count);

    // Adiciona a ação de remover o item
    removeRegionsItem(newForm);

    // Adiciona a validação de licenças escolhidas
    validaRegionsItem(newForm);

    return newForm;
}

/**
 * Remove item da tabela de disponibilidade.
 */
function removeRegionsItem(regionItem){
    regionItem.find("a.regions-remove-item").click(function(e){
        e.preventDefault();
        var item = this;
        var title = $(this).data('confirm-title');
        var text = $(this).data('confirm-text');
        var line = $(item).closest('tr.regions-item');
        $(line).remove();

    });
}

/**
 * Valida se existem dias repetidos na tabela de disponibilidade
 */
function validaRegionsItem(regionItem){
    regionItem.find(".region").change(function(e){
        e.preventDefault();
        var region = $(this);
        var choicedRegion = $(this).val();
        var choicedId = $(this).attr('id');
        $(".region").each(function( i ){
            if(choicedRegion == $(this).val() && ($(this).attr('id') != choicedId) ){
                alert ("Esta região já foi selecionada!");
                region.val("");
            }
        });
    });
}
