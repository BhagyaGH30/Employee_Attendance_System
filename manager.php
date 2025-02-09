<?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "attendance_db"; // Change this if needed

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Count pending leave requests
$leave_count_query = $conn->query("SELECT COUNT(*) AS count FROM leave_requests WHERE status='Pending'");
$leave_count = ($leave_count_query) ? $leave_count_query->fetch_assoc()['count'] : 0;

// Delete Attendance Record
if (isset($_POST['delete_record'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM attendance WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Approve Leave Request
if (isset($_POST['approve_leave'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("UPDATE leave_requests SET status='Approved' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Reject Leave Request
if (isset($_POST['reject_leave'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("UPDATE leave_requests SET status='Rejected' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Export Attendance Data to Excel
if (isset($_POST['export_excel'])) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=attendance_data.xls");
    echo "Employee Name\tDate\tCheck-In\tCheck-Out\tTotal Hours\tStatus\n";

    $result = $conn->query("SELECT * FROM attendance");
    while ($row = $result->fetch_assoc()) {
        echo "{$row['employee_name']}\t{$row['date']}\t{$row['check_in_time']}\t{$row['check_out_time']}\t{$row['total_hours']}\t{$row['status']}\n";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Panel - Attendance</title>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: url('bg1img.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }
        h2, h3 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            color: black;
            border-radius: 5px;
            overflow: hidden;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background:rgb(76, 163, 175);
            color: white;
        }
        button {
            padding: 8px 12px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .delete-btn {
            background: red;
            color: white;
        }
        .delete-btn:hover {
            background: darkred;
        }
        .leave-btn {
            background: <?= ($leave_count > 0) ? 'red' : '#007BFF' ?>;
            color: white;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
        }
        .modal {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            background: black;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        .modal.show {
            display: block;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manager Panel - Attendance Management</h2>

    <form method="post">
        <button type="submit" name="export_excel">Download Excel</button>
        <button type="button" id="openLeaveModal" class="leave-btn">
            Leave Requests (<?= $leave_count ?>)
        </button>
    </form>

    <h3>Employee Attendance</h3>
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM attendance ORDER BY date DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['employee_name']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['check_in_time']}</td>
                        <td>{$row['check_out_time']}</td>
                        <td>{$row['total_hours']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' name='delete_record' class='delete-btn' onclick=\"return confirm('Are you sure?')\">🗑 Delete</button>
                            </form>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div id="leaveRequestModal" class="modal">
    <h3>Leave Requests</h3>
    <?php
    $leaveRequests = $conn->query("SELECT * FROM leave_requests WHERE status='Pending'");
    while ($row = $leaveRequests->fetch_assoc()) {
        echo "<p><b>{$row['employee_name']}</b> requested leave on <b>{$row['leave_date']}</b></p>
              <form method='post'>
                  <input type='hidden' name='id' value='{$row['id']}'>
                  <button type='submit' name='approve_leave'>Approve</button>
                  <button type='submit' name='reject_leave'>Reject</button>
              </form>";
    }
    ?>
    <button id="closeLeaveModal">Close</button>
</div>

<script>
$(document).ready(function() {
    $('#attendanceTable').DataTable();
    $("#openLeaveModal").click(function() {
        $("#leaveRequestModal").addClass("show");
    });
    $("#closeLeaveModal").click(function() {
        $("#leaveRequestModal").removeClass("show");
    });
});
</script>

</body>
</html>
