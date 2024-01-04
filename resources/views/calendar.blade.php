<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>

    <!-- FullCalendar styles -->
    <link href="{{ asset('path/to/fullcalendar/main.css') }}" rel="stylesheet">

    <!-- Moment.js (required for FullCalendar) -->
    <script src="{{ asset('path/to/moment/main.js') }}"></script>

    <!-- FullCalendar scripts -->
    <script src="{{ asset('path/to/fullcalendar/main.js') }}"></script>
    <script src="{{ asset('path/to/fullcalendar/daygrid/main.js') }}"></script>

    <!-- Your custom styles if needed -->
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid'],
                events: [
                    // Add your events here
                    // { title: 'Event 1', date: '2023-01-01' },
                    // { title: 'Event 2', date: '2023-01-02' },
                    // ...
                ]
            });

            calendar.render();
        });
    </script>
</body>
</html>
