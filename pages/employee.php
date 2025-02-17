<?php
include('../includes/db.php');

// Handle Check-In, Check-Out, and Leave Request logic here
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['check_in'])) {
        // Handle check-in
        $employee_id = $_POST['employee_id'];
        $time_in = date('Y-m-d H:i:s');
        $sql = "INSERT INTO attendance (employee_id, check_in) VALUES ('$employee_id', '$time_in')";
        $conn->query($sql);
    } elseif (isset($_POST['check_out'])) {
        // Handle check-out
        $employee_id = $_POST['employee_id'];
        $time_out = date('Y-m-d H:i:s');
        $sql = "UPDATE attendance SET check_out='$time_out' WHERE employee_id='$employee_id' AND check_out IS NULL";
        $conn->query($sql);
    } elseif (isset($_POST['leave_request'])) {
        // Handle leave request
        $employee_id = $_POST['employee_id'];
        $leave_date = $_POST['leave_date'];
        $reason = $_POST['reason'];
        $sql = "INSERT INTO leaves (employee_id, leave_date, reason) VALUES ('$employee_id', '$leave_date', '$reason')";
        $conn->query($sql);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">
    <h2>Employee Attendance System</h2>

    <!-- Form for Check-In and Check-Out -->
    <form method="POST">
        <input type="hidden" name="employee_id" value="1"> <!-- Assuming Employee ID is 1 for this example -->
        <button type="submit" name="check_in" class="btn btn-primary btn-custom">Check-In</button>
        <button type="submit" name="check_out" class="btn btn-danger btn-custom">Check-Out</button>
    </form>

    <!-- Form for Leave Request -->
    <form method="POST">
        <input type="hidden" name="employee_id" value="1">
        <label for="leave_date">Leave Date:</label>
        <input type="date" name="leave_date" required>
        <textarea name="reason" placeholder="Leave Reason" required></textarea>
        <button type="submit" name="leave_request" class="btn btn-warning btn-custom">Request Leave</button>
    </form>

    <!-- Attendance Table -->
    <table id="attendanceTable" class="display">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Check-In</th>
                <th>Check-Out</th>
            </tr>
        </thead>
        <tbody>
            <!-- Fetch Attendance Data -->
            <?php
            $result = $conn->query("SELECT * FROM attendance");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['employee_id']}</td>
                        <td>{$row['check_in']}</td>
                        <td>{$row['check_out']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
</body>
</html>
