<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "attendance_db";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Check-In
if (isset($_POST['check_in'])) {
    $employee_name = trim($_POST['employee_name']);
    $date = date('Y-m-d');
    $check_in_time = date('H:i:s');

    $sql = "SELECT * FROM attendance WHERE employee_name='$employee_name' AND date='$date'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        $status = (strtotime($check_in_time) > strtotime("09:15:00")) ? "Late" : "Present";
        $insert = "INSERT INTO attendance (employee_name, date, check_in_time, status) 
                   VALUES ('$employee_name', '$date', '$check_in_time', '$status')";
        $conn->query($insert);
        echo "<script>alert('Checked in successfully!');</script>";
    } else {
        echo "<script>alert('Already checked in today!');</script>";
    }
}

// Handle Check-Out
if (isset($_POST['check_out'])) {
    $employee_name = trim($_POST['employee_name']);
    $date = date('Y-m-d');
    $check_out_time = date('H:i:s');

    $sql = "SELECT check_in_time FROM attendance WHERE employee_name='$employee_name' AND date='$date'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $check_in_time = $row['check_in_time'];
        $total_hours = round((strtotime($check_out_time) - strtotime($check_in_time)) / 3600, 2);
        $status = ($total_hours >= 8) ? "Present" : (($total_hours >= 4) ? "Half-Day" : "Absent");

        $update = "UPDATE attendance 
                   SET check_out_time='$check_out_time', total_hours='$total_hours', status='$status' 
                   WHERE employee_name='$employee_name' AND date='$date'";
        $conn->query($update);
        echo "<script>alert('Checked out successfully!');</script>";
    } else {
        echo "<script>alert('You must check in first!');</script>";
    }
}

// Handle Leave Request
if (isset($_POST['apply_leave'])) {
    $employee_name = trim($_POST['employee_name']);
    $leave_date = $_POST['leave_date'];
    $leave_reason = trim($_POST['leave_reason']);

    $checkLeave = "SELECT * FROM leave_requests WHERE employee_name='$employee_name' AND leave_date='$leave_date'";
    $result = $conn->query($checkLeave);

    if ($result->num_rows == 0) {
        $insertLeave = "INSERT INTO leave_requests (employee_name, leave_date, leave_reason, status) 
                        VALUES ('$employee_name', '$leave_date', '$leave_reason', 'Pending')";
        $conn->query($insertLeave);
        echo "<script>alert('Leave applied successfully!');</script>";
    } else {
        echo "<script>alert('Leave already recorded for this date!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('bg1img.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
        }
        form {
            margin: 10px 0;
        }
        input, textarea {
            padding: 8px;
            margin: 5px;
            border-radius: 5px;
            border: none;
        }
        button {
            padding: 5px 10px;
            background:rgb(40, 167, 148);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background:rgb(117, 33, 136);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            color: black;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background:rgb(40, 167, 161);
            color: white;
        }
        .leave-form {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
        }
        .leave-form input {
            flex: 1;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Employee Attendance System</h2>

    <!-- Check-in and Check-out -->
    <form method="post">
        <input type="text" name="employee_name" placeholder="Employee Name" required>
        <button type="submit" name="check_in">Check-In</button>
        <button type="submit" name="check_out">Check-Out</button>
    </form>

    <!-- Apply for Leave -->
    <h3>Apply for Leave</h3>
    <form method="post">
        <div class="leave-form">
            <input type="text" name="employee_name" placeholder="Employee Name" required>
            <input type="date" name="leave_date" required>
            <input type="text" name="leave_reason" placeholder="Leave Reason" required>
            <button type="submit" name="apply_leave">Apply Leave</button>
        </div>
    </form>

    <!-- Attendance Table -->
    <h3>Attendance Records</h3>
    <table id="attendanceTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Total Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM attendance";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['employee_name']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['check_in_time']}</td>
                        <td>{$row['check_out_time']}</td>
                        <td>{$row['total_hours']}</td>
                        <td>{$row['status']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#attendanceTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true
        });
    });
</script>

</body>
</html>
