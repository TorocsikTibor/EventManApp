@php use Illuminate\Support\Facades\Auth; @endphp
@extends('app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div id="saveform_errlist"></div>
            <div id="success_message"></div>

            <div class="col-md-10">
                <div class="input-group-prepend">
                    <form method="get">
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
                            <input type="text" class="form-control searchValue" placeholder="Type here..."
                                   name="searchValue">
                        </div>

                        <input type="submit" class="btn btn-primary search" value="Search">
                    </form>
                </div>
            </div>
        </div>


        <div class="card-group row mt-3 justify-content-center add_event"></div>
        <div class="card-group row mt-3 justify-content-center">
            @foreach($events as $event)
                <div class="col-4" id="delete_event{{$event->id}}">
                    <div class="m-2  remove_event">
                        <div class="card">
                            <img class="card-img-top" src="{{$event->getImagePath()}}" alt="Card image cap">
                            <div class="card-body">
                                @if( $event->user_id !== Auth::id() )
                                    <h4 class="card-title">{{ $event->name }}</h4>
                                    @if( in_array($event->id, $joinedEventIds))
                                        <input type="button" class="btn btn-primary" value="Attend"
                                               disabled>
                                    @else
                                        <input type="hidden" class="eventId" value="{{ $event->id }}">
                                        <input type="button" class="btn btn-primary attend"
                                               id="btn_change{{$event->id}}"
                                               value="Attend">
                                    @endif
                                @else
                                    <a class="card-title" href="{{ route('showUpdate', [ 'id' => $event->id ]) }}">
                                        <h4>{{ $event->name }}</h4></a>

                                @endif
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Date: {{ $event->date }}</li>
                                    <li class="list-group-item">Location: {{ $event->location }}</li>
                                    <li class="list-group-item">Type: {{ $event->type }}</li>
                                </ul>
                                <h5>Description:</h5>
                                <p class="card-text">{{ $event->description }}</p>
                                <p>Creator: {{ $event->eventOwner->name }}</p>
                                @if(Auth::id() === $event->user_id)
                                    <div class="flex justify-content-end">
                                        <input type="hidden" class="deleteId" value="{{ $event->id }}">
                                        <input class="btn btn-danger delete_event" type="submit" value="Delete">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="{{ asset('js/search.js') }}"></script>
    <script src="{{ asset('js/attend.js') }}"></script>
    <script src="{{ asset('js/deleteEvent.js') }}"></script>

@endsection
