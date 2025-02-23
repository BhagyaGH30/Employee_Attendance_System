<?php
include 'db.php';

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
    header("Location: manager.php");
    exit();
}

// Approve Leave Request
if (isset($_POST['approve_leave'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("UPDATE leave_requests SET status='Approved' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manager.php");
    exit();
}

// Reject Leave Request
if (isset($_POST['reject_leave'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("UPDATE leave_requests SET status='Rejected' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manager.php");
    exit();
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
    exit();
}
?>
