<?php
// Show error messages
if (isset($_SESSION['msg_error'])) {
?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['msg_error']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
    unset($_SESSION['msg_error']);
}

// Show info messages
if (isset($_SESSION['msg_info'])) {
?>
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['msg_info']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
    unset($_SESSION['msg_info']);
}
?>