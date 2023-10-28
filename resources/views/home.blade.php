@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div id="saveform_errlist">
            </div>

            <div id="success_message">
            </div>

            <div class="col-md-8">
                <a class="btn btn-primary" href="{{ route('showCreate') }}">Create event</a>
            </div>

            <div class="col-md-8">
                <div class="input-group-prepend">
                    <form method="get" action="{{ route('search') }}">
                        <label class="form-label" for="search">Search:</label>
                        <div class="input-group mb-3">
                            <div class="col-2">
                                <select class="form-select select" name="select">
                                    <option value="name">Name</option>
                                    <option value="date">Date</option>
                                    <option value="location">Location</option>
                                    <option value="description">Description</option>
                                </select>
                            </div>
                            <input type="text" class="form-control searchValue" name="searchValue">
                        </div>

                        <input type="submit" class="btn btn-primary search" value="Search">
                    </form>
                </div>
            </div>
            <div class="col-md-8 add_event">
            </div>
            <div class="col-md-8 remove_event">
                @foreach($events as $event)
                    <div class="card" style="width: 24rem;">
                        <img class="card-img-top" src="public/images/{{ $event->image }}" alt="Card image cap">
                        <div class="card-body">
                            <h4><a class="card-title"
                                   href="{{ route('eventUpdate', [ 'id' => $event->id ]) }}">{{ $event->name }}</a></h4>
                            @if( $event->owner_id !== Auth::id() )
                                @if( in_array($event->id, $joinedEventIds))
                                    <input type="button" class="btn btn-primary" value="Attend"
                                           disabled>
                                @else
                                    <input type="hidden" class="eventId" value="{{ $event->id }}">
                                    <input type="button" class="btn btn-primary attend" id="btn_change{{$event->id}}"
                                           value="Attend">
                                @endif
                            @endif
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Date: {{ $event->date }}</li>
                                <li class="list-group-item">Location: {{ $event->location }}</li>
                                <li class="list-group-item">Type: {{ $event->type }}</li>
                            </ul>
                            <h5>Description:</h5>
                            <p class="card-text">{{ $event->description }}</p>
                            <p>Creator: {{ $event->eventOwner->name }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
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
                    url: "{{ route('search') }}",
                    dataType: "json",
                    data: data,
                    success: function (response) {
                        $('.remove_event').remove();
                        $.each(response, function(index, event) {
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
                    error: function(xhr, status, error) {
                        console.error('Failed to fetch event data:', error);
                    }
                });
            });
        });
    </script>

@endsection
