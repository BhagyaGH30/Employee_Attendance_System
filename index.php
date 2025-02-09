<?php
// index.php - Home Page with Employee and Manager Buttons
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Employee & Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background Image */
        body {
            background: url('bgimg.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        /* Overlay Effect */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Dark overlay */
        }
        /* Centered Content Box */
        .container {
            position: relative;
            text-align: center;
            padding: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            color: white;
            z-index: 1;
        }
        /* Stylish Buttons */
        .btn-custom {
            width: 200px;
            font-size: 18px;
            padding: 12px;
            margin: 10px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-custom:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="overlay"></div> <!-- Overlay for better readability -->
    <div class="container">
        <h2 class="mb-4">Welcome , Have a Nice Day</h2>
        <a href="employee.php" class="btn btn-primary btn-custom">Employee</a>
        <a href="manager.php" class="btn btn-success btn-custom">Manager</a>
    </div>
</body>
</html>
