
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
            url: "/home/search",
            dataType: "json",
            data: data,
            success: function (response) {
                $('.remove_event').remove();
                $.each(response, function (index, event) {
                    let cardHTML = `
                    <div class="card" style="width: 24rem;">
                        <img class="card-img-top" src="public/images/${event.image}" alt="Card image cap">
                        <div class="card-body">
                            <h4><a class="card-title" href="/event/${event.id}">${event.name}</a></h4>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Date: ${event.date}</li>
                                <li class="list-group-item">Location: ${event.location}</li>
                                <li class="list-group-item">Type: ${event.type}</li>
                            </ul>
                            <h5>Description:</h5>
                            <p class="card-text">${event.description}</p>
                            <p>Creator: ${event.event_owner.name}</p>
                        </div>
                    </div>`;
                    $(".add_event").append(cardHTML);
                    console.log(event);
                });
            },
            error: function (xhr, status, error) {
                console.error('Failed to fetch event data:', error);
            }
        });
    });
});
