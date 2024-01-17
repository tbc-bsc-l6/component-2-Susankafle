<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Updated Popper.js version -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
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
<a class="navbar-brand" href="{{ url('/') }}">
    Trade Zette
</a>
    <button onclick="changeView('month')">Month</button>
    <button onclick="changeView('agendaWeek')">Week</button>
    <button onclick="changeView('agendaDay')">Day</button>
    <div id="calendar"></div>

    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Your form fields for editing event details -->
                    <label for="editTitle">Title:</label>
                    <input type="text" id="editTitle" name="editTitle">

                    <label for="editEntryPrice">Entry Price:</label>
                    <input type="number" id="editEntryPrice" name="editEntryPrice">

                    <label for="editExitPrice">Exit Price:</label>
                    <input type="number" id="editExitPrice" name="editExitPrice">

                    <label for="editStartDate">Start Date:</label>
                    <input type="date" id="editStartDate" name="editStartDate">

                    <label for="editEndDate">End Date:</label>
                    <input type="date" id="editEndDate" name="editEndDate">

                    <label for="editComment">Comment:</label>
                    <textarea id="editComment" name="editComment"></textarea>
                    <button type="button" class="btn btn-danger" id="deleteEventButton">Delete Event</button>
                    <!-- Save changes button -->
                    <button type="button" id="saveEventChanges">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form fields for creating event details -->
                    <label for="createTitle">Title:</label>
                    <input type="text" id="createTitle" name="createTitle">

                    <label for="createEntryPrice">Entry Price:</label>
                    <input type="number" id="createEntryPrice" name="createEntryPrice">

                    <label for="createExitPrice">Exit Price:</label>
                    <input type="number" id="createExitPrice" name="createExitPrice">

                    <label for="createStartDate">Start Date:</label>
                    <input type="date" id="createStartDate" name="createStartDate">

                    <label for="createEndDate">End Date:</label>
                    <input type="date" id="createEndDate" name="createEndDate">

                    <label for="createComment">Comment:</label>
                    <textarea id="createComment" name="createComment"></textarea>

                    <!-- Save changes button -->
                    <button type="button" id="saveNewEvent">Save Event</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeView(view) {
            $('#calendar').fullCalendar('changeView', view);
        }
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var selectedEventId; // Variable to store the selected event ID

            $(calendarEl).fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                },
                defaultView: 'month',                
                events: {
                    url: '/events', // Adjust the URL based on your Laravel routes
                    method: 'GET',
                    data: {
                
                user_id: '{{ Auth::id() }}',
                
            },
                    failure: function() {
                        alert('There was an error while fetching events!');
                    }
                },
                eventClick: function (event, jsEvent, view) {
                    openEditForm(event);
                },
                dayClick: function(date, jsEvent, view) {                
                    openCreateForm(date);
                },                
            });

            // Attach click event listener to the "Save changes" button inside the modal
            $('#saveEventChanges').on('click', function() {
                saveEventChanges(selectedEventId);
            });
            $('#deleteEventButton').on('click', function () {
            if (selectedEventId) {
                deleteEvent(selectedEventId);
            } else {
                alert('No event selected for deletion.');
            }
            });
            $('#saveNewEvent').on('click', function() {
            createEvent(); // Call the createEvent function when the button is clicked
        });

        function createEvent() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var createForm = $('#createEventModal');

            var eventData = {
                _token: csrfToken,
                title: createForm.find('#createTitle').val(),
                entry_price: createForm.find('#createEntryPrice').val(),
                exit_price: createForm.find('#createExitPrice').val(),
                start_date: createForm.find('#createStartDate').val(),
                end_date: createForm.find('#createEndDate').val(),
                comment: createForm.find('#createComment').val(),
                // ... Get other fields accordingly
            };

            // Send an AJAX request to create the event
            $.ajax({
                type: 'POST',
                url: '/events',
                data: eventData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    console.log('Event created successfully:', response);
                    // Close the modal and update the FullCalendar
                    createForm.modal('hide');
                    $('#calendar').fullCalendar('refetchEvents');
                },
                error: function (xhr, status, error) {
                    console.error('Error creating event:', xhr.responseText);

                    // Handle specific cases
                    if (xhr.status === 422) {
                        // Validation error, display errors to the user
                        var errors = JSON.parse(xhr.responseText);
                        // Display errors as needed
                    } else if (xhr.status === 500) {
                        // Server error, display a generic error message
                        alert('Error creating event. Please try again.');
                    }
                },
            });
        }
        function openCreateForm(date) {
            var createForm = $('#createEventModal');

            // Populate the form fields with default values for creating a new event
            createForm.find('#createTitle').val('New Event');
            createForm.find('#createEntryPrice').val(0);
            createForm.find('#createExitPrice').val(0);
            createForm.find('#createStartDate').val(date.format());
            createForm.find('#createEndDate').val(date.format());
            createForm.find('#createComment').val('');

            // Update modal title to indicate creating a new event
            createForm.find('.modal-title').text('Create Event');

            // Show the modal using Bootstrap's modal method
            createForm.modal('show');
        }
            function deleteEvent(eventId) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: 'DELETE',
                    url: '/events/' + eventId,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('Event deleted successfully:', response);
                        $('#editEventModal').modal('hide');
                        $('#calendar').fullCalendar('refetchEvents');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error deleting event:', xhr.responseText);

                        if (xhr.status === 403) {
                            alert('You do not have permission to delete this event.');
                        } else if (xhr.status === 500) {
                            alert('Error deleting event. Please try again.');
                        }
                    },
                });
            }
            function openEditForm(event) {
                // Set the selected event ID
                selectedEventId = event.id;

                // Assuming you have a form with an ID 'editEventForm'
                var editForm = $('#editEventModal');

                // Populate the form fields with event details
                editForm.find('#editTitle').val(event.title);
                editForm.find('#editEntryPrice').val(event.entry_price);
                editForm.find('#editExitPrice').val(event.exit_price);
                editForm.find('#editStartDate').val(event.start_date);
                editForm.find('#editEndDate').val(event.end_date);
                editForm.find('#editComment').val(event.comment);

                // Show the modal using Bootstrap's modal method
                editForm.modal('show');
            }
            function saveEventChanges(eventId) {
                // Assuming you have a form with an ID 'editEventForm'
                var editForm = $('#editEventModal');

                // Get the data from the form
                var editedEventData = {
                    _token: '{{ csrf_token() }}',
                    title: editForm.find('#editTitle').val(),
                    entry_price: editForm.find('#editEntryPrice').val(),
                    exit_price: editForm.find('#editExitPrice').val(),
                    start_date: editForm.find('#editStartDate').val(),
                    end_date: editForm.find('#editEndDate').val(),
                    comment: editForm.find('#editComment').val(),
                };

                // Send an AJAX request to update the event
                $.ajax({
                    type: 'PUT', // Use PUT method for updating
                    url: '/events/' + eventId, // Adjust the URL based on your Laravel routes
                    data: editedEventData,
                    success: function (response) {
                        console.log('Event updated successfully:', response);
                        // Close the modal and update the FullCalendar
                        editForm.modal('hide');
                        $('#calendar').fullCalendar('refetchEvents');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error updating event:', xhr.responseText);

                        // Handle specific cases
                        if (xhr.status === 422) {
                            // Validation error, display errors to the user
                            var errors = JSON.parse(xhr.responseText);
                            // Display errors as needed
                        } else if (xhr.status === 500) {
                            // Server error, display a generic error message
                            alert('Error updating event. Please try again.');
                        }
                    },
                });
            }
        });
    </script>
    @include('footer')
</body>
</html>