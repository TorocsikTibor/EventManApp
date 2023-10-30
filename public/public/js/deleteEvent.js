$(document).ready(function () {
    $(document).on('click', '.delete_event', function (e) {
        e.preventDefault();

        let eventId = $(this).siblings(".deleteId").val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "DELETE",
            url: "/event/delete/"+eventId,
            success: function (result) {
                $('#success_message').html("");
                $('#success_message').addClass('alert alert-success');
                $('#success_message').text('Event deleted');
                $('#delete_event'+eventId).remove();
            }
        });
    });
});
