<?php 
include 'db.php';
include 'process_manager.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Panel - Attendance</title>
    <link rel="stylesheet" href="styles.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>
<body>

<div class="container">
    <h2>Manager Panel - Attendance Management</h2>

    <form method="post" action="process_manager.php">
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
                            <form method='post' action='process_manager.php' style='display:inline;'>
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
              <form method='post' action='process_manager.php'>
                  <input type='hidden' name='id' value='{$row['id']}'>
                  <button type='submit' name='approve_leave'>Approve</button>
                  <button type='submit' name='reject_leave'>Reject</button>
              </form>";
    }
    ?>
    <button id="closeLeaveModal">Close</button>
</div>
<script src="script.js"></script>
</body>
</html>
