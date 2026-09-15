<?php
$role = $_SESSION['role'];
$current = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
<?php if ($role == 'seeker') { ?>
<a class="<?php echo $current=='dashboard.php'?'active':''; ?>" href="/ResQLink/seeker/dashboard.php">Dashboard</a>
<a class="<?php echo $current=='requests.php'?'active':''; ?>" href="/ResQLink/seeker/requests.php">Shelter Requests</a>
<a class="<?php echo $current=='family.php'?'active':''; ?>" href="/ResQLink/seeker/family.php">Family Members</a>
<a class="<?php echo $current=='contacts.php'?'active':''; ?>" href="/ResQLink/seeker/contacts.php">Emergency Contacts</a>
<?php } elseif ($role == 'volunteer') { ?>
<a class="<?php echo $current=='dashboard.php'?'active':''; ?>" href="/ResQLink/volunteer/dashboard.php">Dashboard</a>
<a class="<?php echo $current=='tasks.php'?'active':''; ?>" href="/ResQLink/volunteer/tasks.php">Tasks</a>
<a class="<?php echo $current=='availability.php'?'active':''; ?>" href="/ResQLink/volunteer/availability.php">Availability</a>
<a class="<?php echo $current=='activities.php'?'active':''; ?>" href="/ResQLink/volunteer/activities.php">Support Activities</a>
<?php } else { ?>
<a class="<?php echo $current=='dashboard.php'?'active':''; ?>" href="/ResQLink/admin/dashboard.php">Dashboard</a>
<a class="<?php echo $current=='shelters.php'?'active':''; ?>" href="/ResQLink/admin/shelters.php">Manage Shelters</a>
<a class="<?php echo $current=='requests.php'?'active':''; ?>" href="/ResQLink/admin/requests.php">Requests</a>
<a class="<?php echo $current=='volunteers.php'?'active':''; ?>" href="/ResQLink/admin/volunteers.php">Volunteers</a>
<a class="<?php echo $current=='users.php'?'active':''; ?>" href="/ResQLink/admin/users.php">Manage Users</a>
<?php } ?>
<a class="<?php echo $current=='profile.php'?'active':''; ?>" href="/ResQLink/profile.php">Profile</a>
<a href="/ResQLink/logout.php">Logout</a>
</aside>
