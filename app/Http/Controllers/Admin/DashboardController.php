<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Node;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
{
    // Query the database to get the counts
    $family_count = Node::count(); // Count of family members
    $events_count = Event::count(); // Count of events

    // Return the view with the data
    return view('admin.dashboard', compact('family_count', 'events_count'));
}
}