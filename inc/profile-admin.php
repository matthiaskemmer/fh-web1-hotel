<?php include 'dbaccess.php'; ?>
<?php include("require_admin.php"); ?>
<?php

if (!isset($_GET["id"])) {
    $_SESSION["msg_error"] = "Profil konnte nicht gefunden werden.";
    header('Location: index.php?site=guests-admin');
    return;
}
$user_id = $_GET["id"];
$findresult = "SELECT profiles.anrede, profiles.first_name, profiles.last_name, profiles.email, users.user_name, users.user_password
FROM profiles
JOIN users ON profiles.fk_user_id = users.user_id
WHERE users.user_id = ?";
$stmt = $mysqli->prepare($findresult);
$stmt->bind_param('i', $_GET["id"]);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($anrede, $firstname, $lastname, $email, $username, $userpwd);
$stmt->fetch();

?>

<div class="row justify-content-center mb-4">
    <div class="col-md-10 col-lg-8 col-xl-6">
        <?php include("alert.php"); ?>
        <div class="card text-center shadow-lg mb-5 p-5">
            <h1 class="mb-md-4">User Daten</h1>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th scope="row">Anrede</th>
                        <td>
                            <?php
                            if ($anrede == 'F') {
                                echo "Frau";
                            } else if ($anrede == 'M') {
                                echo "Herr";
                            } else {
                                echo "n.A.";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Vorname</th>
                        <td>
                            <?php echo $firstname; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Nachname</th>
                        <td>
                            <?php echo $lastname; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Username</th>
                        <td><?php echo $username; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">E-Mail</th>
                        <td>
                            <?php echo $email; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="d-grid gap- justify-content-center">
                <a class="btn btn-outline-primary me-2" type="submit" name="edit" href="index.php?site=profile-update-admin&id=<?= $user_id ?>" value="Daten ändern">Daten ändern</a>
            </div>
        </div>
    </div>
</div>