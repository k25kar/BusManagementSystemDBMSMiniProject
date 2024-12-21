<?php
require 'db_connect.php';

if (isset($_GET['trip_id'])) {
    $trip_id = intval($_GET['trip_id']);
    
    // First get the bus ID to find out total seats
    $bus_query = "SELECT b.total_seats FROM Trip t JOIN Bus b ON t.bus_id = b.bus_id WHERE t.trip_id = ?";
    
    // Prepare and execute bus query
    $stmtBus = $conn->prepare($bus_query);
    $stmtBus->bind_param("i", $trip_id);
    $stmtBus->execute();
    
    // Fetch total seats
    $bus_result = $stmtBus->get_result();
    $bus_row = $bus_result->fetch_assoc();
    
    // Get booked seats
    $seats_query = "SELECT seat_number FROM Ticket WHERE trip_id = ?";
    
    // Prepare and execute seats query
    $stmtSeats = $conn->prepare($seats_query);
    $stmtSeats->bind_param("i", $trip_id);
    $stmtSeats->execute();
    
    // Fetch booked seats
    $seats_result = $stmtSeats->get_result();
    
    // Prepare response data
    $seats_data = [];
    
    while ($rowSeat = $seats_result->fetch_assoc()) {
        array_push($seats_data, [
            'number' => (int)$rowSeat['seat_number'],
            'status' => 'booked'
        ]);
    }

    echo json_encode([
        'total_seats' => (int)$bus_row['total_seats'],
        'seats' => $seats_data
    ]);
}
?>