$(document).ready(function () {
    $(document).on('click', '.attend', function (e) {
        e.preventDefault();

        let eventId = $(this).siblings(".eventId").val();

        let data = {
            'eventId': eventId,
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "event/attend/" + eventId,
            dataType: "json",
            data: data,
            success: function (response) {
                if (response.status == 200) {
                    $('#success_message').html("");
                    $('#success_message').addClass('alert alert-success');
                    $('#success_message').text('Joined to the event');
                    $('#btn_change' + eventId).attr("disabled", true);
                } else {
                    $('#saveform_errlist').html("");
                    $('#saveform_errlist').addClass('alert alert-danger');
                    $.each(response.errors, function (key, err_values) {
                        $('#saveform_errlist').append('<li>' + err_values + '</li>');
                    });
                }
            }
        });
    });
});
