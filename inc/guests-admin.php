<?php include 'dbaccess.php'; ?>
<?php

// Load users
$sql = "SELECT u.user_id, u.user_name, u.user_status, u.fk_role_id, p.last_name, p.first_name from users u JOIN profiles p ON u.user_id = p.fk_user_id WHERE u.fk_role_id = 2;";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($user_id, $user_name, $user_status, $user_role, $user_last, $user_first);

if ($stmt->num_rows == 0) {
    $_SESSION["msg_info"] = "Es existieren noch User.";
}
?>
<div class="row justify-content-center mb-4">
    <div class="col-md-10 col-lg-8 col-xl-6">
        <?php include("require_admin.php"); ?>
        <?php include("alert.php"); ?>
        <h1 class="mb-md-4">Userverwaltung</h1>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Name</th>
                        <th scope="col">ID</th>
                        <th scope="col">Status</th>
                        <th scope="col">Profil</th>
                        <th scope="col">Buchungen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < $stmt->num_rows; $i++) {
                        $stmt->fetch();
                    ?>
                        <tr class="text-center">
                            <th scope="row" class="text-end"><?= $i+1 ?></th>
                            <td class="text-start"><?= $user_name ?></td>
                            <td><?= $user_first ?> <span class="text-decoration-underline"><?= $user_last ?></span></td>
                            <td scope="row" class="text-end"><?= $user_id ?></td>
                            <td>
                                <?php
                                if ($user_status == 1) {
                                    echo '<span class="badge rounded-pill text-bg-success">Aktiv</span>';
                                } else if ($user_status == 0) {
                                    echo '<span class="badge rounded-pill text-bg-secondary">Inaktiv</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <a class="btn btn-secondary btn-sm" href="index.php?site=profile-admin&id=<?= $user_id ?>">Anzeigen</a>
                            </td>
                            <td>
                                <a class="btn btn-secondary btn-sm" href="index.php?site=room-admin&status=0&id=<?= $user_id ?>">Anzeigen</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>