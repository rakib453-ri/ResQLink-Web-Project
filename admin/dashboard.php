<?php
include "../includes/auth.php"; require_role('admin'); include "../config/database.php";
$shelters=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM shelters"))['total'];
$requests=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM shelter_requests"))['total'];
$volunteers=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM users WHERE role='volunteer' AND status='active'"))['total'];
$pending=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM shelter_requests WHERE status='pending'"))['total'];
$users=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM users"))['total'];
$completed=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM tasks WHERE status='completed'"))['total'];
$recent=mysqli_query($conn,"SELECT r.id,r.status,r.location,s.name AS shelter_name FROM shelter_requests r LEFT JOIN shelters s ON r.assigned_shelter_id=s.id ORDER BY r.id DESC LIMIT 3");
$pageTitle="Admin Dashboard"; include "../includes/header.php";
?>
<div class="layout"><?php include "../includes/sidebar.php"; ?><section class="content"><div class="dashboard-grid"><div class="dash-stat"><div class="label">Shelters</div><div class="value"><?php echo $shelters; ?></div></div><div class="dash-stat green"><div class="label">Requests</div><div class="value"><?php echo $requests; ?></div></div><div class="dash-stat orange"><div class="label">Volunteers</div><div class="value"><?php echo $volunteers; ?></div></div><div class="dash-stat purple"><div class="label">Pending</div><div class="value"><?php echo $pending; ?></div></div></div><div class="panel-grid"><div class="panel orange"><h3>Recent Requests</h3><?php while($row=mysqli_fetch_assoc($recent)){ ?><div class="info-row"><span>REQ-<?php echo str_pad($row['id'],3,'0',STR_PAD_LEFT); ?></span><span><?php echo clean($row['shelter_name'] ?: $row['location']); ?></span><span><?php echo clean($row['status']); ?></span></div><?php } ?></div><div class="panel blue"><h3>System Summary</h3><p class="summary-line">Total Users: <?php echo $users; ?></p><p class="summary-line">Active Volunteers: <?php echo $volunteers; ?></p><p class="summary-line">Completed Tasks: <?php echo $completed; ?></p><p class="summary-line">System Status: Operational</p></div></div></section></div><?php include "../includes/footer.php"; ?>
