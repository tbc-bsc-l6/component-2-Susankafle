<?php

// app/Http/Controllers/CalendarController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function showCalendar()
    {
        return view('calendar.index');
    }
}
