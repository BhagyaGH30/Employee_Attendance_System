<?php
include 'db.php';

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
        echo "<script>alert('Checked in successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Already checked in today!'); window.location.href='index.php';</script>";
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
        echo "<script>alert('Checked out successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('You must check in first!'); window.location.href='index.php';</script>";
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
        echo "<script>alert('Leave applied successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Leave already recorded for this date!'); window.location.href='index.php';</script>";
    }
}
?>
