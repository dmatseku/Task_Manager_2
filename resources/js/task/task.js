$(document).ready(function() {
    $('#name-form').submit(function() {
        sendAjax($(this), $('#name-field'), function(form, field, data) {
            field.removeClass('border-danger');
            form.find('.invalid-feedback').remove();
        }, function(form, field, jqXHR) {
            let error = JSON.parse(jqXHR.responseText);

            field.addClass('border-danger');
            form.find('.invalid-feedback').remove();
            form.find('.form-group').append(buildError(error['errors']['name'][0], true));
        })

        return false;
    });

    $('#description-form').submit(function() {
        sendAjax($(this), $('#description-field'), function(form, field, data) {
            field.removeClass('border-danger');
            form.find('.invalid-feedback').remove();
        }, function(form, field, jqXHR) {
            let error = JSON.parse(jqXHR.responseText);

            field.addClass('border-danger');
            form.find('.invalid-feedback').remove();
            form.find('.form-group').append(buildError(error['errors']['description'][0], true));
        })

        return false;
    });

    $('#type-form').submit(function() {
        sendAjax($(this), $('#type-field'), function(form, field, data) {},
            function(form, field, jqXHR) {})

        return false;
    });

    $('#btn-next-status').submit(function() {
        sendAjax($(this), $('#not-exists'), function(form, field, data) {
            updateStatusButton(data['status']);
        }, function(form, field, data) {});

        return false;
    });

    $('#dates-form').submit(function() {
        sendAjax($(this), $('#not-exists'), function(form, field, data) {
            $('#begin-field, #finish-field').removeClass('is-invalid');
            updateStatusButton(data['status']);
            form.find('.invalid-feedback').remove();
        }, function(form, field, jqXHR) {
            let error = JSON.parse(jqXHR.responseText);

            form.find('.invalid-feedback').remove();
            if ('begin_in' in error['errors']) {
                $('#begin-field').addClass('is-invalid')
                    .after(buildError(error['errors']['begin_in'][0], false));
            }
            if ('finish_in' in error['errors']) {
                $('#finish-field').addClass('is-invalid')
                    .after(buildError(error['errors']['finish_in'][0], false));
            }
        });

        return false;
    });


    $('#name-field').change(function() {
        $('#name-form').submit();
    });

    $('#description-field').change(function() {
        $('#description-form').submit();
    });

    $('#type-field').change(function() {
        $('#type-form').submit();
    });

    $('#begin-field, #finish-field').change(function() {
        $('#dates-form').submit();
    });
});



function lockPanel()
{
    $('#buttons-panel').find('button, a').prop('disabled', true);
    $('#saving-status').text('Saving');
}

function unlockPanel(message)
{
    $('#buttons-panel').find('button, a').prop('disabled', false);
    $('#saving-status').text(message);
}

function sendAjax(form, field, successFunction, errorFunction)
{
    let sendData = form.serialize();

    field.prop("disabled", true);
    lockPanel();

    $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: sendData
    }).done(function(data) {
        successFunction(form, field, data);
        unlockPanel('Saved');
        field.prop("disabled", false);
    }).fail(function(jqXHR, textStatus) {
        errorFunction(form, field, jqXHR);
        unlockPanel('Error');
        field.prop("disabled", false);
    });
}

function buildError(message, center)
{
    return ('<span class="invalid-feedback d-block '+ (center ? 'text-center ' : '') + 'mt-0" role="alert">\n' +
        '    <strong>' + message + '</strong>\n' +
        '</span>');
}

function updateStatusButton(status) {
    let form = $("#btn-next-status");
    let button = form.find('button');

    button.removeClass('btn-begin btn-finish');
    if (status > 2) {
        form.removeClass('d-block').addClass('d-none');
    } else {
        form.removeClass('d-none').addClass('d-block');
        form.css('display', 'block');
        if (status === 2) {
            button.addClass('btn-finish');
            button.find('span').text('Finish');
        } else {
            button.addClass('btn-begin');
            button.find('span').text('Begin');
        }
    }
}
