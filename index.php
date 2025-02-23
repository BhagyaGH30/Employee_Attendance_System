<?php
// index.php - Home Page with Employee and Manager Buttons
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Employee & Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="overlay"></div> <!-- Overlay for better readability -->
    <div class="container">
        <h2 class="mb-4">Welcome , Have a Nice Day</h2>
        <a href="attendance.php" class="btn btn-primary btn-custom">Employee</a>
        <a href="manager.php" class="btn btn-success btn-custom">Manager</a>
    </div>
    <script src="script.js"></script>
</body>
</html>

