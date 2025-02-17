<?php
include('../includes/db.php');

// Handle Leave Approvals, Rejections, and Record Deletions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve_leave'])) {
        // Approve leave request
        $leave_id = $_POST['leave_id'];
        $sql = "UPDATE leaves SET status='Approved' WHERE leave_id='$leave_id'";
        $conn->query($sql);
    } elseif (isset($_POST['reject_leave'])) {
        // Reject leave request
        $leave_id = $_POST['leave_id'];
        $sql = "UPDATE leaves SET status='Rejected' WHERE leave_id='$leave_id'";
        $conn->query($sql);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Panel - Attendance</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">
    <h2>Manager Panel</h2>

    <!-- Manage Leave Requests -->
    <h3>Leave Requests</h3>
    <table id="leaveTable" class="display">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Leave Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Fetch Leave Data -->
            <?php
            $result = $conn->query("SELECT * FROM leaves");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['employee_id']}</td>
                        <td>{$row['leave_date']}</td>
                        <td>{$row['reason']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <form method='POST' style='display:inline-block'>
                                <input type='hidden' name='leave_id' value='{$row['leave_id']}'>
                                <button type='submit' name='approve_leave' class='btn btn-success btn-custom'>Approve</button>
                            </form>
                            <form method='POST' style='display:inline-block'>
                                <input type='hidden' name='leave_id' value='{$row['leave_id']}'>
                                <button type='submit' name='reject_leave' class='btn btn-danger btn-custom'>Reject</button>
                            </form>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
</body>
</html>
