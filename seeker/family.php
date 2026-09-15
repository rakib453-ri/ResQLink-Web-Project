<?php
include "../includes/auth.php";
require_role('seeker');
include "../config/database.php";
$user_id = (int)$_SESSION['user_id'];
$edit = null;

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM family_members WHERE id=$id AND seeker_user_id=$user_id");
    set_message("Family member deleted.");
    header("Location: family.php"); exit();
}
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM family_members WHERE id=$id AND seeker_user_id=$user_id");
    $edit = mysqli_fetch_assoc($result);
}
if (isset($_POST['save_family'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $age = (int)$_POST['age'];
    $relationship = mysqli_real_escape_string($conn, trim($_POST['relationship']));
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $special_needs = mysqli_real_escape_string($conn, trim($_POST['special_needs']));

    if ($id > 0) {
        mysqli_query($conn, "UPDATE family_members SET name='$name',age=$age,relationship='$relationship',gender='$gender',special_needs='$special_needs' WHERE id=$id AND seeker_user_id=$user_id");
        set_message("Family member updated.");
    } else {
        mysqli_query($conn, "INSERT INTO family_members (seeker_user_id,name,age,relationship,gender,special_needs) VALUES ($user_id,'$name',$age,'$relationship','$gender','$special_needs')");
        set_message("Family member added.");
    }
    header("Location: family.php"); exit();
}
$rows = mysqli_query($conn, "SELECT * FROM family_members WHERE seeker_user_id=$user_id ORDER BY id DESC");
$pageTitle = "Family Members";
include "../includes/header.php";
?>
<div class="layout"><?php include "../includes/sidebar.php"; ?><section class="content">
<h2>Family / Dependent Management</h2><?php show_message(); ?>
<div class="section"><h3><?php echo $edit ? 'Edit Family Member' : 'Add Family Member'; ?></h3>
<form method="post" class="form-grid"><input type="hidden" name="id" value="<?php echo $edit ? $edit['id'] : 0; ?>">
<div class="form-group"><label>Name</label><input name="name" required value="<?php echo clean($edit['name'] ?? ''); ?>"></div>
<div class="form-group"><label>Age</label><input type="number" min="0" name="age" required value="<?php echo clean($edit['age'] ?? ''); ?>"></div>
<div class="form-group"><label>Relationship</label><input name="relationship" required value="<?php echo clean($edit['relationship'] ?? ''); ?>"></div>
<div class="form-group"><label>Gender</label><select name="gender"><option value="">Select</option><option <?php if (($edit['gender'] ?? '')=='Male') echo 'selected'; ?>>Male</option><option <?php if (($edit['gender'] ?? '')=='Female') echo 'selected'; ?>>Female</option><option <?php if (($edit['gender'] ?? '')=='Other') echo 'selected'; ?>>Other</option></select></div>
<div class="form-group full"><label>Special Needs</label><input name="special_needs" value="<?php echo clean($edit['special_needs'] ?? ''); ?>"></div>
<div class="form-group full"><button class="btn" name="save_family"><?php echo $edit ? 'Update' : 'Add'; ?></button></div></form></div>
<div class="table-wrap"><table><tr><th>Name</th><th>Age</th><th>Relationship</th><th>Gender</th><th>Special Needs</th><th>Actions</th></tr>
<?php while ($row = mysqli_fetch_assoc($rows)) { ?><tr><td><?php echo clean($row['name']); ?></td><td><?php echo $row['age']; ?></td><td><?php echo clean($row['relationship']); ?></td><td><?php echo clean($row['gender']); ?></td><td><?php echo clean($row['special_needs']); ?></td><td class="actions"><a class="btn small" href="?edit=<?php echo $row['id']; ?>">Edit</a><a class="btn small danger" data-confirm="Delete this family member?" href="?delete=<?php echo $row['id']; ?>">Delete</a></td></tr><?php } ?>
</table></div></section></div><?php include "../includes/footer.php"; ?>
