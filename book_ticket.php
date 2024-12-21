<?php
require 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $trip_id = $_POST['trip_id'];
    $seat_number = $_POST['seat_number'];

    $query = "CALL BookTicket(?, ?, ?, @ticket_details)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $user_id, $trip_id, $seat_number);
    $stmt->execute();

    $result = $conn->query("SELECT @ticket_details AS ticket_details");
    $row = $result->fetch_assoc();
    $ticket_details = $row['ticket_details'];

    if (strpos($ticket_details, 'successfully') !== false) {
        preg_match('/Ticket booked successfully: (\d+)/', $ticket_details, $matches);
        if (isset($matches[1])) {
            $_SESSION['ticket_id'] = $matches[1]; // Store ticket ID in session
        }
        header("Location: view_bookings.php"); // Redirect to view_bookings.php
        exit();
    } else {
        $error_message = $ticket_details;
    }

   // Close statement after execution
   $stmt->close();
}

// Fetch trips for the dropdown
$trips = [];
$trip_query = "SELECT t.trip_id, b.bus_number, r.source, r.destination, t.date
               FROM Trip t
               JOIN Bus b ON t.bus_id = b.bus_id
               JOIN Route r ON t.route_id = r.route_id
               ORDER BY t.date";
$trip_result = $conn->query($trip_query);
while ($trip_row = $trip_result->fetch_assoc()) {
   $trips[] = $trip_row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Book Ticket</title>
   <link rel="stylesheet" href="styles.css"> <!-- Ensure this path is correct -->
   <script>
       function loadSeats(tripId) {
           fetch(`get_seats.php?trip_id=${tripId}`)
               .then(response => response.json())
               .then(data => {
                   let seatContainer = document.getElementById('seat-container');
                   seatContainer.innerHTML = '';

                   const totalSeats = data.total_seats;

                   for (let i = 1; i <= totalSeats; i++) {
                       let seatStatus = 'available';
                       let bookedSeats = data.seats.map(seat => seat.number);

                       if (bookedSeats.includes(i)) {
                           seatStatus = 'booked';
                       }

                       let seatBox = document.createElement('div');
                       seatBox.className = 'seat ' + seatStatus;
                       seatBox.innerText = i;
                       seatBox.dataset.seatNumber = i;

                       seatBox.onclick = function() {
                           if (seatStatus === 'available') {
                               document.querySelectorAll('.seat.selected').forEach(s => s.classList.remove('selected'));
                               seatBox.classList.add('selected');
                               document.getElementById('seat_number').value = i;
                           }
                       };
                       seatContainer.appendChild(seatBox);
                   }
               });
       }
   </script>
</head>
<body>
   <div class="container">
       <header>
           <h1>Book a Ticket</h1>
       </header>

       <?php if ($error_message): ?>
           <p style="color:red;"><?php echo htmlspecialchars($error_message); ?></p>
       <?php endif; ?>

       <form action="book_ticket.php" method="post">
           <label for="trip_id">Select Trip:</label>
           <select name="trip_id" id="trip_id" onchange="loadSeats(this.value)" required>
               <option value="">--Select a Trip--</option>
               <?php foreach ($trips as $trip): ?>
                   <option value="<?= htmlspecialchars($trip['trip_id']) ?>">
                       <?= htmlspecialchars("{$trip['source']} to {$trip['destination']} on {$trip['date']} (Bus: {$trip['bus_number']})") ?>
                   </option>
               <?php endforeach; ?>
           </select>

           <div id="seat-container" class="seat-container"></div>

           <input type="hidden" name="seat_number" id="seat_number">
           <button type="submit">Book Ticket</button>
       </form>

       <!-- Go Back Button -->
       <a href="dashboard.php" style="margin-top: 20px; display: inline-block; text-align: center; background-color: #214d79; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Go Back</a>
   </div>
</body>
</html>