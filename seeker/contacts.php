<?php
include "../includes/auth.php";
require_role('seeker');
include "../config/database.php";
$user_id = (int)$_SESSION['user_id'];
$edit = null;

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM emergency_contacts WHERE id=$id AND seeker_user_id=$user_id");
    set_message("Emergency contact deleted.");
    header("Location: contacts.php"); exit();
}
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM emergency_contacts WHERE id=$id AND seeker_user_id=$user_id");
    $edit = mysqli_fetch_assoc($result);
}
if (isset($_POST['save_contact'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $relationship = mysqli_real_escape_string($conn, trim($_POST['relationship']));

    if ($id > 0) {
        mysqli_query($conn, "UPDATE emergency_contacts SET name='$name',phone='$phone',email='$email',relationship='$relationship' WHERE id=$id AND seeker_user_id=$user_id");
        set_message("Contact updated.");
    } else {
        mysqli_query($conn, "INSERT INTO emergency_contacts (seeker_user_id,name,phone,email,relationship) VALUES ($user_id,'$name','$phone','$email','$relationship')");
        set_message("Contact added.");
    }
    header("Location: contacts.php"); exit();
}
$rows = mysqli_query($conn, "SELECT * FROM emergency_contacts WHERE seeker_user_id=$user_id ORDER BY id DESC");
$pageTitle = "Emergency Contacts";
include "../includes/header.php";
?>
<div class="layout"><?php include "../includes/sidebar.php"; ?><section class="content"><h2>Emergency Contact Management</h2><?php show_message(); ?>
<div class="section"><h3><?php echo $edit ? 'Edit Contact' : 'Add Contact'; ?></h3>
<form method="post" class="form-grid"><input type="hidden" name="id" value="<?php echo $edit ? $edit['id'] : 0; ?>">
<div class="form-group"><label>Name</label><input name="name" required value="<?php echo clean($edit['name'] ?? ''); ?>"></div>
<div class="form-group"><label>Phone</label><input name="phone" required value="<?php echo clean($edit['phone'] ?? ''); ?>"></div>
<div class="form-group"><label>Email</label><input type="email" name="email" value="<?php echo clean($edit['email'] ?? ''); ?>"></div>
<div class="form-group"><label>Relationship</label><input name="relationship" required value="<?php echo clean($edit['relationship'] ?? ''); ?>"></div>
<div class="form-group full"><button class="btn" name="save_contact"><?php echo $edit ? 'Update' : 'Add'; ?></button></div></form></div>
<div class="table-wrap"><table><tr><th>Name</th><th>Phone</th><th>Email</th><th>Relationship</th><th>Actions</th></tr>
<?php while ($row = mysqli_fetch_assoc($rows)) { ?><tr><td><?php echo clean($row['name']); ?></td><td><?php echo clean($row['phone']); ?></td><td><?php echo clean($row['email']); ?></td><td><?php echo clean($row['relationship']); ?></td><td class="actions"><a class="btn small" href="?edit=<?php echo $row['id']; ?>">Edit</a><a class="btn small danger" data-confirm="Delete this contact?" href="?delete=<?php echo $row['id']; ?>">Delete</a></td></tr><?php } ?>
</table></div></section></div><?php include "../includes/footer.php"; ?>
