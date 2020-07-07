$(document).ready(function() {
    $("#search").submit(function() {
        let form = $(this);
        let sendData = form.serialize();
        let searchLine = form.find("input:text");

        searchLine.prop("disabled", true);

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: sendData
        }).done(function(data) {
            $('#tbody').html(data);

            searchLine.removeClass('is-invalid');
            searchLine.prop("disabled", false);
        }).fail(function(jqXHR, textStatus) {
            console.log('Search error');
            searchLine.addClass('is-invalid');
            searchLine.prop("disabled", false);
        });

        return false;
    });

    $(".next-status").submit(function() {
        let form = $(this);
        let sendData = form.serialize();
        let statusButton = form.find("button");
        let deleteButton = form.closest(".cell-actions").find(".delete").find("button");
        let statusCell = form.closest(".list-group-item").find(".cell-status");

        statusButton.prop('disabled', true);
        deleteButton.prop('disabled', true);
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: sendData
        }).done(function(data) {
            statusCell.removeClass('bg-created bg-began bg-finished');
            statusButton.removeClass('btn-begin btn-finish');

            switch (data['status']) {
                case 1:
                    statusCell.addClass('bg-created');
                    statusCell.text("Begin in " + data['begin_in']);
                    statusButton.addClass('btn-begin');
                    statusButton.find('span').text("Begin");
                    break;
                case 2:
                    statusCell.addClass('bg-began');
                    statusCell.text("Finish in " + data['finish_in']);
                    statusButton.addClass('btn-finish');
                    statusButton.find('span').text("Finish");
                    break;
                case 3:
                    statusCell.addClass('bg-finished');
                    statusCell.text("Finished");
                    form.remove();
                    break;
            }

            statusButton.prop('disabled', false);
            deleteButton.prop('disabled', false);
        }).fail(function(jqXHR, textStatus) {
            console.log("Next status error");
            statusButton.prop('disabled', false);
            deleteButton.prop('disabled', false);
        });

        return false;
    })

    $('.delete').submit(function() {
        let form = $(this);
        let sendData = form.serialize();
        let statusButton = form.closest(".cell-actions").find(".next-status").find("button");
        let deleteButton = form.find("button");
        let listItem = form.closest(".list-group-item");

        statusButton.prop('disabled', true);
        deleteButton.prop('disabled', true);
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: sendData
        }).done(function(data) {
            if (data['success']) {
                listItem.remove();
            } else {
                statusButton.prop('disabled', false);
                deleteButton.prop('disabled', false);
            }
        }).fail(function(jqXHR, textStatus) {
            console.log('Delete error');
            statusButton.prop('disabled', false);
            deleteButton.prop('disabled', false);
        });

        return false;
    });
});
