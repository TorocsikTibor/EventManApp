@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div id="saveform_errlist"></div>

            <div id="success_message"></div>

            <div class="col-md-8">
                <a class="btn btn-primary" href="{{ route('showCreate') }}">Create event</a>
            </div>

            <div class="col-md-10">
                <div class="input-group-prepend">
                    <form method="get" action="{{ route('search') }}">
                        <label class="form-label" for="search">Search:</label>
                        <div class="input-group mb-3">
                            <div class="col-4">
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
            <div class="col-md-8 add_event"></div>
            <div class="col-md-8 remove_event">
                @foreach($events as $event)
                    <div class="card" style="width: 22rem;">
                        <img class="card-img-top" src="public/images/{{ $event->image }}" alt="Card image cap">
                        <div class="card-body">
                            @if( $event->owner_id !== Auth::id() )
                                <h4 class="card-title">{{ $event->name }}</h4>
                                @if( in_array($event->id, $joinedEventIds))
                                    <input type="button" class="btn btn-primary" value="Attend"
                                           disabled>
                                @else
                                    <input type="hidden" class="eventId" value="{{ $event->id }}">
                                    <input type="button" class="btn btn-primary attend" id="btn_change{{$event->id}}"
                                           value="Attend">
                                @endif
                            @else
                                <a class="card-title" href="{{ route('eventUpdate', [ 'id' => $event->id ]) }}"><h4>{{ $event->name }}</h4></a>

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

    <script src="{{ asset('public/js/search.js') }}"></script>
    <script src="{{ asset('public/js/append.js') }}"></script>

@endsection
