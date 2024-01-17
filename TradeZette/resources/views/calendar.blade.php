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
    <!-- Bootstrap CSS (adjust the link based on your Bootstrap version) -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- FullCalendar styles -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css" rel="stylesheet">
    <!-- FullCalendar scripts -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/gcal.min.js"></script>
    <!-- Bootstrap JS (adjust the link based on your Bootstrap version) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
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

    <!-- Bootstrap modal for editing events -->
    <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Your form fields go here -->
                    <form id="editEventForm">
                        <!-- Add your input fields here -->
                        <div class="form-group">
                            <label for="editTitle">Title</label>
                            <input type="text" class="form-control" id="editTitle" required>
                        </div>
                        <!-- Add other fields accordingly -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveEventChanges">Save changes</button>
                </div>
            </div>
        </div>
    </div>

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
                eventClick: function (event, jsEvent, view) {
                    openEditForm(event);
                },
                dayClick: function(date, jsEvent, view) {                
                    handleDateClick(date);
                    //window.location.href = '/event/create';
                },           
            });
        });

        function openEditForm(event) {
            // Assuming you have a form with an ID 'editEventForm'
            var editForm = $('#editEventModal');

            // Populate the form fields with event details
            editForm.find('#editTitle').val(event.title);
            // ... Populate other fields accordingly

            // Show the modal
            editForm.modal('show');

            // Handle form submission to update the event
            editForm.off('submit').on('submit', function (e) {
                e.preventDefault();

                // Your existing AJAX request to update the event
                // ...

                // Close the modal after updating the event
                editForm.modal('hide');
            });
        }

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
