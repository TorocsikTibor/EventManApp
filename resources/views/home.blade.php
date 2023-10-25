@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a class="btn btn-primary" href="{{ route('showCreate') }}">Create event</a>
        </div>

        <div class="col-md-8">
            @foreach($events as $event)
            <div class="card" style="width: 24rem;">
                <img class="card-img-top" src="public/images/{{ $event->image }}" alt="Card image cap">
                <div class="card-body">
                    <h4><a class="card-title" href="{{ route('eventUpdate', [ 'id' => $event->id ]) }}">{{ $event->name }}</a></h4>
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
@endsection
