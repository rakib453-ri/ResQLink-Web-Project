<?php
require_once __DIR__ . "/../includes/auth.php"; require_role("seeker");
require_once __DIR__ . "/../config/database.php";
$uid=$_SESSION["user_id"];
function c1($conn,$sql,$uid){$s=$conn->prepare($sql);$s->bind_param("i",$uid);$s->execute();return (int)$s->get_result()->fetch_row()[0];}
$r=c1($conn,"SELECT COUNT(*) FROM shelter_requests WHERE seeker_user_id=?",$uid);
$f=c1($conn,"SELECT COUNT(*) FROM family_members WHERE seeker_user_id=?",$uid);
$c=c1($conn,"SELECT COUNT(*) FROM emergency_contacts WHERE seeker_user_id=?",$uid);
$s=$conn->prepare("SELECT * FROM shelter_requests WHERE seeker_user_id=? ORDER BY id DESC LIMIT 1");$s->bind_param("i",$uid);$s->execute();$latest=$s->get_result()->fetch_assoc();
$pageTitle="Shelter Seeker Dashboard"; include __DIR__ . "/../includes/header.php";?>
<div class="layout"><?php include __DIR__ . "/../includes/sidebar.php";?><section class="content">
<?php show_flash();?>
<div class="dashboard-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">
 <div class="dash-stat"><div class="label">My Requests</div><div class="value"><?=$r?></div></div>
 <div class="dash-stat green"><div class="label">Family Members</div><div class="value"><?=$f?></div></div>
 <div class="dash-stat orange"><div class="label">Emergency Contacts</div><div class="value"><?=$c?></div></div>
</div>
<div class="panel-grid">
 <div class="panel blue"><h3>Latest Shelter Request</h3>
 <?php if($latest): ?>
  <p class="summary-line">Request ID: REQ-<?=str_pad($latest['id'],3,'0',STR_PAD_LEFT)?></p>
  <p class="summary-line">Location: <?=e($latest['location'])?></p>
  <p class="summary-line">People: <?=$latest['people_count']?></p>
  <p class="summary-line">Status: <?=e(ucfirst($latest['status']))?></p>
 <?php else: ?><p class="empty-note">No shelter request yet.</p><?php endif; ?>
 </div>
 <div class="panel"><h3>Quick Actions</h3><div class="quick-actions">
  <a class="btn-outline" href="/ResQLink/seeker/requests.php">+ New Shelter Request</a>
  <a class="btn-outline" href="/ResQLink/seeker/family.php">+ Add Family Member</a>
  <a class="btn-outline" href="/ResQLink/seeker/contacts.php">+ Add Emergency Contact</a>
 </div></div>
</div>
</section></div><?php include __DIR__ . "/../includes/footer.php";?>