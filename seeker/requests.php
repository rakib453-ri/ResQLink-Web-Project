<?php
include "../includes/auth.php";
require_role('seeker');
include "../config/database.php";

$user_id = (int)$_SESSION['user_id'];
$edit = null;

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM shelter_requests WHERE id=$id AND seeker_user_id=$user_id");
    set_message("Request deleted.");
    header("Location: requests.php");
    exit();
}

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM shelter_requests WHERE id=$id AND seeker_user_id=$user_id");
    $edit = mysqli_fetch_assoc($result);
}

if (isset($_POST['save_request'])) {
    $id = (int)$_POST['id'];
    $location = mysqli_real_escape_string($conn, trim($_POST['location']));
    $people_count = (int)$_POST['people_count'];
    $emergency_type = mysqli_real_escape_string($conn, $_POST['emergency_type']);
    $special_needs = mysqli_real_escape_string($conn, trim($_POST['special_needs']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));

    if ($id > 0) {
        $sql = "UPDATE shelter_requests SET location='$location', people_count=$people_count,
                emergency_type='$emergency_type', special_needs='$special_needs', description='$description'
                WHERE id=$id AND seeker_user_id=$user_id";
        mysqli_query($conn, $sql);
        set_message("Request updated.");
    } else {
        $sql = "INSERT INTO shelter_requests (seeker_user_id,location,people_count,emergency_type,special_needs,description)
                VALUES ($user_id,'$location',$people_count,'$emergency_type','$special_needs','$description')";
        mysqli_query($conn, $sql);
        set_message("Request created.");
    }
    header("Location: requests.php");
    exit();
}

$rows = mysqli_query($conn, "SELECT r.*, s.name AS shelter_name FROM shelter_requests r
                             LEFT JOIN shelters s ON r.assigned_shelter_id=s.id
                             WHERE r.seeker_user_id=$user_id ORDER BY r.id DESC");
$pageTitle = "Shelter Requests";
include "../includes/header.php";
?>
<div class="layout">
<?php include "../includes/sidebar.php"; ?>
<section class="content">
<h2>Shelter Request Management</h2>
<?php show_message(); ?>
<div class="section">
<h3><?php echo $edit ? 'Edit Request' : 'Create New Request'; ?></h3>
<form method="post" class="form-grid">
<input type="hidden" name="id" value="<?php echo $edit ? $edit['id'] : 0; ?>">
<div class="form-group"><label>Location</label><input name="location" required value="<?php echo clean($edit['location'] ?? ''); ?>"></div>
<div class="form-group"><label>People Count</label><input type="number" min="1" name="people_count" required value="<?php echo clean($edit['people_count'] ?? 1); ?>"></div>
<div class="form-group"><label>Emergency Type</label><select name="emergency_type" required>
<option value="Flood" <?php if (($edit['emergency_type'] ?? '')=='Flood') echo 'selected'; ?>>Flood</option><option value="Cyclone" <?php if (($edit['emergency_type'] ?? '')=='Cyclone') echo 'selected'; ?>>Cyclone</option><option value="Fire" <?php if (($edit['emergency_type'] ?? '')=='Fire') echo 'selected'; ?>>Fire</option><option value="Other" <?php if (($edit['emergency_type'] ?? '')=='Other') echo 'selected'; ?>>Other</option>
</select></div>
<div class="form-group"><label>Special Needs</label><input name="special_needs" value="<?php echo clean($edit['special_needs'] ?? ''); ?>"></div>
<div class="form-group full"><label>Description</label><textarea name="description"><?php echo clean($edit['description'] ?? ''); ?></textarea></div>
<div class="form-group full"><button class="btn" name="save_request"><?php echo $edit ? 'Update' : 'Create'; ?></button></div>
</form>
</div>
<div class="table-wrap"><table>
<tr><th>ID</th><th>Location</th><th>People</th><th>Type</th><th>Status</th><th>Assigned Shelter</th><th>Actions</th></tr>
<?php while ($row = mysqli_fetch_assoc($rows)) { ?>
<tr><td><?php echo $row['id']; ?></td><td><?php echo clean($row['location']); ?></td><td><?php echo $row['people_count']; ?></td><td><?php echo clean($row['emergency_type']); ?></td><td><?php echo clean($row['status']); ?></td><td><?php echo clean($row['shelter_name'] ?? 'Not assigned'); ?></td><td class="actions"><a class="btn small" href="?edit=<?php echo $row['id']; ?>">Edit</a><a class="btn small danger" data-confirm="Delete this request?" href="?delete=<?php echo $row['id']; ?>">Delete</a></td></tr>
<?php } ?>
</table></div>
</section></div>
<?php include "../includes/footer.php"; ?>
