<?php
include "../includes/auth.php";
require_role('seeker');
include "../config/database.php";
$user_id = (int)$_SESSION['user_id'];

$r1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM shelter_requests WHERE seeker_user_id=$user_id");
$r2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM family_members WHERE seeker_user_id=$user_id");
$r3 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM emergency_contacts WHERE seeker_user_id=$user_id");
$request_count = mysqli_fetch_assoc($r1)['total'];
$family_count = mysqli_fetch_assoc($r2)['total'];
$contact_count = mysqli_fetch_assoc($r3)['total'];
$latest_result = mysqli_query($conn, "SELECT * FROM shelter_requests WHERE seeker_user_id=$user_id ORDER BY id DESC LIMIT 1");
$latest = mysqli_fetch_assoc($latest_result);

$pageTitle = "Shelter Seeker Dashboard";
include "../includes/header.php";
?>
<div class="layout"><?php include "../includes/sidebar.php"; ?><section class="content"><?php show_message(); ?>
<div class="dashboard-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">
<div class="dash-stat"><div class="label">My Requests</div><div class="value"><?php echo $request_count; ?></div></div>
<div class="dash-stat green"><div class="label">Family Members</div><div class="value"><?php echo $family_count; ?></div></div>
<div class="dash-stat orange"><div class="label">Emergency Contacts</div><div class="value"><?php echo $contact_count; ?></div></div>
</div>
<div class="panel-grid"><div class="panel blue"><h3>Latest Shelter Request</h3>
<?php if ($latest) { ?><p class="summary-line">Request ID: REQ-<?php echo str_pad($latest['id'],3,'0',STR_PAD_LEFT); ?></p><p class="summary-line">Location: <?php echo clean($latest['location']); ?></p><p class="summary-line">People: <?php echo $latest['people_count']; ?></p><p class="summary-line">Status: <?php echo clean(ucfirst($latest['status'])); ?></p><?php } else { ?><p>No shelter request yet.</p><?php } ?>
</div><div class="panel green"><h3>Quick Actions</h3><p><a class="btn" href="requests.php">New Shelter Request</a></p><p><a class="btn secondary" href="family.php">Add Family Member</a></p><p><a class="btn secondary" href="contacts.php">Add Emergency Contact</a></p></div></div>
</section></div><?php include "../includes/footer.php"; ?>
