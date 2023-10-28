$(document).ready(function () {
    $(document).on('click', '.delete_event', function (e) {
        e.preventDefault();

        let id = $('.id').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "DELETE",
            url: "/event/delete/"+id,
            // data: id,
            success: function (result) {
                $('#success_message').html("");
                $('#success_message').addClass('alert alert-success');
                $('#success_message').text('Event deleted');
            }
        });
    });
});
