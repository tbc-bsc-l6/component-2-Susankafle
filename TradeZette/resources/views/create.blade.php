<form id="eventForm" action="{{ route('event.store') }}" method="POST">
    @csrf
    <label for="title">Title:</label>
    <input type="text" name="title" required><br>

    <label for="entry_price">Entry Price:</label>
    <input type="number" name="entry_price" required><br>

    <label for="exit_price">Exit Price:</label>
    <input type="number" name="exit_price" required><br>

    <label for="profit">Profit:</label>
    <input type="number" name="profit" required><br>

    <label for="start_date">Start Date:</label>
    <input type="text" name="start_date" required><br>

    <label for="end_date">End Date:</label>
    <input type="text" name="end_date" required><br>

    <label for="comment">Comment:</label>
    <textarea name="comment"></textarea><br>

    <button type="submit">Submit</button>
</form>

<script>
    // Use JavaScript to set the date values when the form is submitted
    document.getElementById('eventForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var dateClickInfo = $('#calendar').fullCalendar('getDate');
        var start_date = dateClickInfo.format();

        // Set the start_date and end_date input values
        document.getElementById('start_date').value = start_date;
        document.getElementById('end_date').value = start_date;

        // Submit the form
        $.ajax({
            type: 'POST',
            url: '/event',
            data: $(this).serialize(),
            success: function (response) {
                console.log('Server response:', response);
                $('#calendar').fullCalendar('refetchEvents');
            },
            error: function (error) {
                console.error('Error creating event:', error);
            }

           
