<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
    <link rel="stylesheet" href="styles.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
</head>
<body>

<div class="container">
    <h2>Employee Attendance System</h2>

    <!-- Check-in and Check-out -->
    <form method="post" action="process_attendance.php">
        <input type="text" name="employee_name" placeholder="Employee Name" required>
        <button type="submit" name="check_in">Check-In</button>
        <button type="submit" name="check_out">Check-Out</button>
    </form>

    <!-- Apply for Leave -->
    <h3>Apply for Leave</h3>
    <form method="post" action="process_attendance.php">
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
<script src="script.js"></script>
</body>
</html>
