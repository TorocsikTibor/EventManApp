$(document).ready(function () {
    $(document).on('click', '.search', function (e) {
        e.preventDefault();

        let searchValue = $('.searchValue').val();
        let select = $(".select").val();

        let data = {
            'select': select,
            'searchValue': searchValue,
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: "/event/search",
            dataType: "json",
            data: data,
            success: function (response) {
                $('.remove_event').remove();
                let searchedEvents = response.searchedEvents;
                let authenticatedUserId = response.AuthId;
                $.each(searchedEvents, function (index, event) {

                    let cardHTML = '<div class="col-4" id="delete_event' + authenticatedUserId + '">' +
                        '<div class="m-2  remove_event">' +
                        '<div class="card">' +
                        '<img class="card-img-top" src="public/images/' + event.image + '" alt="Card image cap">' +
                        '<div class="card-body">';

                    if (event.owner_id !== authenticatedUserId) {
                        cardHTML += '<h4 class="card-title">' + event.name + '</h4>';

                        if (event.user_visibility.includes(event.id)) {
                            cardHTML += '<input type="button" class="btn btn-primary" value="Attend" disabled="disabled">';
                        } else {
                            cardHTML += '<input type="hidden" class="eventId" value="' + event.id + '">' +
                                '<input type="button" class="btn btn-primary attend" id="btn_change' + event.id + '" value="Attend">';
                        }
                    } else {
                        cardHTML += '<a class="card-title" href="/eventUpdate/' + event.id + '">' +
                            '<h4>' + event.name + '</h4>' +
                            '</a>';
                    }

                    cardHTML += '<ul class="list-group list-group-flush">' +
                        '<li class="list-group-item">Date: ' + event.date + '</li>' +
                        '<li class="list-group-item">Location: ' + event.location + '</li>' +
                        '<li class="list-group-item">Type: ' + event.type + '</li>' +
                        '</ul>' +
                        '<h5>Description:</h5>' +
                        '<p class="card-text">' + event.description + '</p>' +
                        '<p>Creator: ' + event.event_owner.name + '</p>';
                    if (authenticatedUserId === event.owner_id) {
                        cardHTML += '<div class="flex justify-content-end">' +
                            '<input type="hidden" class="deleteId" value="' + event.id + '">' +
                            '<input class="btn btn-danger delete_event" type="submit" value="Delete">' +
                            '</div>';
                    }
                    cardHTML += '</div>' +
                        '</div>' +
                        '</div>';
                    $(".add_event").append(cardHTML);
                });
            },
            error: function (xhr, status, error) {
                console.error('Failed to fetch event data:', error);
            }
        });
    });
});
