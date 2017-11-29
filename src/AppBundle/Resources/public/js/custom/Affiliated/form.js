$(document).ready(function () {

    // Aplica o select2 nos campos definidos
    var applySelect2 = function () {
        $(".select2").select2();
    }

    // Inicialização das tabelas
    initiateLaboratories();

    // Adiciona novo item ao horário
    $("#laboratory-add").click(function (e) {
        e.preventDefault();
        addLaboratoryItem();
        applySelect2();
    });

    // Seta a função de exclusão nos itens da tabela de disponibilidade
    $("#laboratories-table tbody").children().each(function () {
        var laboratoryItem = $(this).closest('tr.laboratory-item');
        removeLaboratoryItem(laboratoryItem);

    });

    applySelect2();

});

/**
 * Função para inicializar o indice da tabela de disponibilidade
 */
function initiateLaboratories() {
    // Atualiza o indice do prototype com a quantidade de itens disponíveis
    $('#laboratories-table').attr('data-index-count', $(".laboratory-item").length);
}

/**
 * Método que adiciona um novo dia e horário de disponibilidade.
 */
function addLaboratoryItem() {
    var collectionHolder = $('#laboratories-table');
    var prototype = collectionHolder.attr('data-prototype');
    var count = collectionHolder.attr('data-index-count');
    var newForm = $(prototype.replace(/\_\_name\_\_/g, count));

    collectionHolder.find('tbody').append(newForm);
    collectionHolder.attr('data-index-count', ++count);

    // Adiciona a ação de remover o item
    removeLaboratoryItem(newForm);

    // Adiciona a validação de licenças escolhidas
    validaLaboratoryItem(newForm);

    return newForm;
}

/**
 * Remove item da tabela de disponibilidade.
 */
function removeLaboratoryItem(laboratoryItem) {
    laboratoryItem.find("a.laboratory-remove-item").click(function (e) {
        console.log("teste");
        e.preventDefault();
        var item = this;
        var title = $(this).data('confirm-title');
        var text = $(this).data('confirm-text');
        var line = $(item).closest('tr.laboratory-item');
        $(line).remove();

    });
}

/**
 * Valida se existem dias repetidos na tabela de disponibilidade
 */
function validaLaboratoryItem(laboratoryItem) {
    laboratoryItem.find(".laboratory").change(function (e) {
        e.preventDefault();
        var laboratory = $(this);
        var choicedLaboratory = $(this).val();
        var choicedId = $(this).attr('id');
        $(".laboratory").each(function (i) {
            if (choicedLaboratory == $(this).val() && ($(this).attr('id') != choicedId)) {
                alert("Este Laboratório já foi selecionado!");
                laboratory.val("");
            }
        });
    });
}
