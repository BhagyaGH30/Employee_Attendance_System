$(document).ready(function() {
    $('#attendanceTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true
    });
});

$(document).ready(function() {
    $('#attendanceTable').DataTable();
    $("#openLeaveModal").click(function() {
        $("#leaveRequestModal").addClass("show");
    });
    $("#closeLeaveModal").click(function() {
        $("#leaveRequestModal").removeClass("show");
    });
});