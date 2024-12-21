<?php
require 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$bookings = [];

// Fetch bookings for the user
$query = "SELECT ti.ticket_id, tr.trip_id, tr.departure_time, tr.arrival_time, ti.seat_number, ti.status 
          FROM Ticket ti 
          JOIN Trip tr ON ti.trip_id = tr.trip_id 
          WHERE ti.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

// Handle cancellation request
if (isset($_GET['cancel_ticket'])) {
    $ticket_id = intval($_GET['cancel_ticket']);
    
    // Call the stored procedure to cancel the ticket
    $cancel_query = "CALL CancelTicket(?)";
    $cancel_stmt = $conn->prepare($cancel_query);
    $cancel_stmt->bind_param("i", $ticket_id);
    
    if ($cancel_stmt->execute()) {
        // Redirect back to view bookings after cancellation
        header("Location: view_bookings.php");
        exit();
    } else {
        // Handle error (optional)
        echo "<p style='color:red;'>Error canceling ticket.</p>";
    }
    
    $cancel_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Your Bookings</h1>
    </header>

    <?php if (empty($bookings)): ?>
        <p>No bookings found.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f4f4f9;">
                    <th>Ticket ID</th>
                    <th>Trip ID</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Seat Number</th>
                    <th>Status</th>
                    <th>Action</th> <!-- New column for action -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr style="border-bottom: 1px solid #ccc;">
                        <td><?= htmlspecialchars($booking['ticket_id']); ?></td>
                        <td><?= htmlspecialchars($booking['trip_id']); ?></td>
                        <td><?= htmlspecialchars($booking['departure_time']); ?></td>
                        <td><?= htmlspecialchars($booking['arrival_time']); ?></td>
                        <td><?= htmlspecialchars($booking['seat_number']); ?></td>
                        <td><?= htmlspecialchars($booking['status']); ?></td>
                        <td>
                            <?php if ($booking['status'] === 'booked'): ?>
                                <!-- Cancel button with link -->
                                <a href="?cancel_ticket=<?= htmlspecialchars($booking['ticket_id']); ?>" style="color:red;">Cancel</a>
                            <?php else: ?>
                                <!-- Show 'Canceled' if already canceled -->
                                Canceled
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Go Back Button -->
    <a href="dashboard.php" style="margin-top: 20px; display: inline-block; text-align: center; background-color: #214d79; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Go Back</a>

</div>
</body>
</html>
