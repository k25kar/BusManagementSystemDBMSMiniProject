# Bus Ticket Management System

# Overview

The Bus Ticket Management System is a web-based application designed to facilitate online ticket booking for bus services. It provides a user-friendly interface for passengers and administrators to manage bookings, view schedules, and handle other essential tasks. Built with PHP, HTML, CSS, JavaScript, and MySQL, this project demonstrates efficient use of full-stack web development technologies.

# Features

User Authentication: Secure login and registration system for passengers.

Dashboard: A centralized hub for managing bookings and viewing schedules.

Booking Tickets: Easy ticket booking process with real-time validation.

View Bookings: A detailed view of booked tickets and trip details.

Responsive Design: Optimized for both desktop and mobile devices.

# Tech Stack

Frontend: HTML, CSS, JavaScript

Backend: PHP

Database: MySQL

# File Structure

index.php: Homepage with navigation to login and register.

register.php: User registration form.

login.php: User authentication system.

dashboard.php: Dashboard displaying user-specific options and features.

book_ticket.php: Page to book new tickets.

view_bookings.php: Displays the user's booking history.

db_connect.php: Database connection and setup.

styles.css: Custom styles for the application.

# How to Run

Clone the repository:

``` git clone https://github.com/k25kar/BusManagementSystemDBMSMiniProject.git ```

Navigate to the project directory:

```cd BusManagementSystemDBMSMiniProject```

Import the database:

Open phpMyAdmin or a MySQL client.

Import the provided SQL file (database.sql) to set up the required tables and data.

Configure database credentials:

Update db_connect.php with your MySQL username, password, and database name.

Start a local server:

Use XAMPP, WAMP, or any other local PHP server.

Access the application:

Open http://localhost/BusManagementSystemDBMSMiniProject/ in your browser.

# Future Enhancements

Admin Panel: Add administrative features to manage buses, routes, and schedules.

Payment Integration: Enable online payment for ticket bookings.

Email Notifications: Send booking confirmations via email.
