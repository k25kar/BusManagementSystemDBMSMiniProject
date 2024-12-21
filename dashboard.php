<!-- dashboard.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h2>Dashboard</h2>
            <main>
                <p>Welcome!<br>Select an option from below to manage your bookings.</p>
            </main>
            <nav>
            <div class="action-buttons">
                <button onclick="window.location.href='book_ticket.php'" class="custom-button">Book Ticket</button>
                <button onclick="window.location.href='view_bookings.php'" class="custom-button">Manage Bookings</button>
                <button onclick="window.location.href='index.php'" class="custom-button-logout">Logout</button>
            </div>
            </nav>
        </header>
    </div>
</body>
</html>
