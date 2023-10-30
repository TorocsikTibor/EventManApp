@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div id="saveform_errlist">
                </div>

                <div id="success_message">
                </div>

                <h4>Update event</h4>
                <form id="AddEventForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" class="id" value="{{ $event->id }}">
                    <div class="form-label">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control name" value="{{ $event->name }}" name="name">
                    </div>
                    <div class="form-label">
                        <label for="date">Date:</label>
                        <input type="datetime-local" class="form-control date" value="{{ $event->date }}" name="date">
                    </div>
                    <div class="form-label">
                        <label for="location">Location:</label>
                        <input type="text" class="form-control location" value="{{ $event->location }}" name="location">
                    </div>
                    <div class="form-label">
                        <label for="image">Image:</label>
                        <input type="file" class="form-control image" name="image" value="{{ $event->image }}"
                               id="image">
                    </div>
                    <div class="form-label">
                        <label for="type">Type:</label>
                        <input type="text" class="form-control type" value="{{ $event->type }}" name="type">
                    </div>
                    <div class="form-label">
                        <label for="description">Description:</label>
                        <textarea class="form-control description"
                                  name="description">{{ $event->description }}</textarea>
                    </div>
                    <div class="form-label">
                        <input type="checkbox" class="form-check-input checkbox" name="checkbox" id="toggleInput"
                               value="1" checked>
                        <label class="form-check-label" for="private">Private</label>
                    </div>
                    <div class="form-label" id="inputField">
                        <label>Who can see the event</label>
                        <select class="form-select users" name="users[]" multiple=""
                                aria-label="multiple select example">
                            @foreach($users as $user)
                                <option
                                    value="{{$user->id}}" {{ in_array($user->id, $selectedUsers) ? 'selected' : '' }}>{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-label">
                        <input type="submit" class="btn btn-primary update_event" value="Update">
                        <a href="{{ route('home') }}" class="btn btn-primary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('public/js/updateEvent.js') }}"></script>

@endsection
