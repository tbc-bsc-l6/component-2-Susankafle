<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                currentTimezone: 'America/Los_Angeles', // an option!
                events: {
                    url: '/events', // Adjust the URL based on your Laravel routes
                    method: 'GET',
                    failure: function() {
                        alert('There was an error while fetching events!');
                    }
                },
                dayClick: function(date, jsEvent, view) {                
                    handleDateClick(date);
                    //window.location.href = '/event/create';
            },           
            });
        });
        function handleDateClick(date) {
            var eventData = {
                _token: '{{ csrf_token() }}', // Add this
                title: 'New Event',
                entry_price: 0,
                exit_price: 0,
                profit: 0,
                start_date: date.format(),
                end_date: date.format(),
                comment: '',
            };
            $.ajax({
                type: 'POST',
                url: '/event', // Change this to the correct URL for your store method
                data: eventData,
                success: function(response) {
                console.log('Server response:', response);
                $('#calendar').fullCalendar('renderEvent', eventData, true);
                },
                error: function(error) {
                    console.error('Error creating event:', error);
                }
            });
        }
        function changeView(view) {
            $('#calendar').fullCalendar('changeView', view);
        }
    </script>
</body>
</html>
