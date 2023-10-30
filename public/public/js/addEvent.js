$(document).ready(function () {
    $('#toggleInput').change(function () {
        if (this.checked) {
            $('#inputField').show();
        } else {
            $('#inputField').hide();
        }
    });

    $(document).on('click', '.add_event', function (e) {
        e.preventDefault();

        let formData = new FormData($('#AddEventForm')[0]);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "/event/create",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 200) {
                    $('#success_message').html("");
                    $('#success_message').addClass('alert alert-success');
                    $('#success_message').text('Event created successfully');
                }
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
