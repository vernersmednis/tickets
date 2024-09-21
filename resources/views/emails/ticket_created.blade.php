<!DOCTYPE html>
<html>
<head>
    <title>New Ticket Created</title>
</head>
<body>
    <h1>A new ticket has been created!</h1>
    <p>
        Click the link below to edit the ticket:
        <a href="{{ route('tickets.edit', $ticket->id) }}">Edit Ticket</a>
    </p>
</body>
</html>
