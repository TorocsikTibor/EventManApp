<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View|Application|Factory
    {

        $visibleEvents = Event::with('userVisibility:id')->where('is_private', 0)->orWhereHas('userVisibility', function ($q) {
            $q->where('users.id', Auth::id());
        })->get();

        $joinedEventIds = [];

        $eventUsers = Event::with('user:id')->whereHas('user', function ($q) {
            $q->where('users.id', Auth::id());
        })->get();

        foreach ($eventUsers as $eventUser) {
            $joinedEventIds[] = $eventUser->id;
        }

        return view('home', ['events' => $visibleEvents, 'joinedEventIds' => $joinedEventIds]);
    }
}
