<!-- resources/views/calendar.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <!-- Moment.js (required for FullCalendar) -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.js"></script>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- FullCalendar styles -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css" rel="stylesheet">

    <!-- FullCalendar scripts -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/gcal.min.js"></script>
    <style>
        /* Style for custom buttons */
        .custom-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 999;
        }

        .custom-buttons button {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<button onclick="changeView('month')">Month</button>
    <button onclick="changeView('agendaWeek')">Week</button>
    <button onclick="changeView('agendaDay')">Day</button>
    <div id="calendar"></div>

    <!-- Add custom buttons outside the calendar -->
    

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            $(calendarEl).fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                },
                defaultView: 'month',
                googleCalendarId: 'your-google-calendar-id@group.calendar.google.com',
                className: 'gcal-event', // an option!
                currentTimezone: 'America/Los_Angeles' // an option!
            });
        });
        // Assume you have a function to handle date clicks in your FullCalendar setup

    function handleDateClick(date) {
    // Get necessary information for the event (e.g., title, start, end)
    var eventData = {
        title: 'New Event',
        start: date.format(),
        end: date.format(),
    };

    // Send an AJAX request to the server to create the event
    $.ajax({
        type: 'POST',
        url: '/events',
        data: eventData,
        success: function(response) {
            // Update the calendar view with the new event
            $('#calendar').fullCalendar('renderEvent', eventData, true);
        },
        error: function(error) {
            console.error('Error creating event:', error);
        }
    });
}

        // Function to change FullCalendar view
        function changeView(view) {
            $('#calendar').fullCalendar('changeView', view);
        }
    </script>
</body>
</html>
