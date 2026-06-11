$(function() {
    $(".kanban-cards").sortable({
        connectWith: ".kanban-cards",
        update: function(event, ui) {
            let cardId = ui.item.data("id");
            let newStage = ui.item.parent().parent().data("stage");
            $.post("actualizar_etapa.php", { id: cardId, etapa: newStage });
        }
    }).disableSelection();
});