$(document).ready(function () {
    $('#toggleInput').change(function () {
        if (this.checked) {
            $('#inputField').show();
        } else {
            $('#inputField').hide();
        }
    });

    $(document).on('click', '.update_event', function (e) {
        e.preventDefault();

        let formData = new FormData($('#AddEventForm')[0]);
        let id = $('.id').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url:  id,
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function () {
                $('#success_message').html("");
                $('#success_message').addClass('alert alert-success');
                $('#success_message').text('Event updated successfully');
            },
            error: function (response) {
                $('#saveform_errlist').html("");
                $('#saveform_errlist').addClass('alert alert-danger');
                $.each(response.responseJSON.errors, function (key, err_values) {
                    $('#saveform_errlist').append('<li>' + err_values + '</li>');
                });
            }
        });
    });
});
