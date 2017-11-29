$(document).ready(function () {

    // Aplica o select2 nos campos definidos
    var applySelect2 = function () {
        $(".select2").select2();
    }

    // Inicialização das tabelas
    initiateAgents();

    // Adiciona novo item ao horário
    $("#agents-add").click(function (e) {
        e.preventDefault();
        addAgentsItem();
        applySelect2();
    });

    // Seta a função de exclusão nos itens da tabela de disponibilidade
    $("#agents-table tbody").children().each(function () {
        var agentItem = $(this).closest('tr.agents-item');
        removeAgentsItem(agentItem);

    });

    applySelect2();

});

/**
 * Função para inicializar o indice da tabela de disponibilidade
 */
function initiateAgents() {
    // Atualiza o indice do prototype com a quantidade de itens disponíveis
    $('#agents-table').attr('data-index-count', $(".agents-item").length);
}

/**
 * Método que adiciona um novo dia e horário de disponibilidade.
 */
function addAgentsItem() {
    var collectionHolder = $('#agents-table');
    var prototype = collectionHolder.attr('data-prototype');
    var count = collectionHolder.attr('data-index-count');
    var newForm = $(prototype.replace(/\_\_name\_\_/g, count));

    collectionHolder.find('tbody').append(newForm);
    collectionHolder.attr('data-index-count', ++count);

    // Adiciona a ação de remover o item
    removeAgentsItem(newForm);

    // Adiciona a validação de licenças escolhidas
    validaAgentsItem(newForm);

    return newForm;
}

/**
 * Remove item da tabela de disponibilidade.
 */
function removeAgentsItem(agentItem) {
    agentItem.find("a.agents-remove-item").click(function (e) {
        e.preventDefault();
        var item = this;
        var title = $(this).data('confirm-title');
        var text = $(this).data('confirm-text');
        var line = $(item).closest('tr.agents-item');
        $(line).remove();

    });
}

/**
 * Valida se existem dias repetidos na tabela de disponibilidade
 */
function validaAgentsItem(agentItem) {
    agentItem.find(".agent").change(function (e) {
        e.preventDefault();
        var agent = $(this);
        var choicedAgent = $(this).val();
        var choicedId = $(this).attr('id');
        $(".agent").each(function (i) {
            if (choicedAgent == $(this).val() && ($(this).attr('id') != choicedId)) {
                alert("Este Representante de Vendas já foi selecionado!");
                agent.val("");
            }
        });
    });
}
