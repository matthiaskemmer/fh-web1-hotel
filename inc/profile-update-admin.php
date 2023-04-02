<?php include 'dbaccess.php'; ?>
<?php include("require_admin.php"); ?>
<?php

if (!isset($_GET["id"])) {
    $_SESSION["msg_error"] = "Profil konnte nicht gefunden werden.";
    header('Location: index.php?site=guests-admin');
    return;
}
$user_id = $_GET["id"];
$findresult = "SELECT profiles.anrede, profiles.first_name, profiles.last_name, profiles.email, users.user_name, users.user_password, users.user_status
FROM profiles
JOIN users ON profiles.fk_user_id = users.user_id
WHERE users.user_id = ?";
$stmt = $mysqli->prepare($findresult);
// Check if the prepare statement failed
if (!$stmt) {
    echo "Error 1: " . $mysqli->error;
    exit();
}
$stmt->bind_param('i', $user_id);
$stmt->execute();
// Check if the execute statement failed
if (!$stmt->execute()) {
    echo "Error 2: " . $stmt->error;
    exit();
}
$stmt->store_result();
$stmt->bind_result($oldAnrede, $oldFirstname, $oldLastname, $oldEmail, $oldUsername, $oldUserpwd, $oldUserStatus);

if (!$stmt->fetch()) {
    echo "Error 3:" . $stmt->error;
    exit();
}
?>


<div class="row justify-content-center mb-4">
    <div class="col-md-10 col-lg-8 col-xl-6">
        <?php include("alert.php"); ?>
        <h1>Profilverwaltung</h1>

        <?php
        // General validation:
        $vorname = $nachname = $username = $email = $newpassword1 = $newpassword2 = "";
        $vornameValid = $nachnameValid = $usernameValid = $emailValid = $passwordValid = $confirmValid = "";
        if (isset($_POST['profileupdate'])) {
            $vorname = test_input($_POST["vorname"]);
            $nachname = test_input($_POST["nachname"]);
            $email = test_input($_POST["email"]);
            $newpassword1 = test_input($_POST["newpassword1"]);
            $newpassword2 = test_input($_POST["newpassword2"]);
        }

        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>

        <!--                    D   A   T   E   N   B   A   N   K               U   E   B   E   R   T   R   A   G   U   N   G        -->
        <?php
        if (isset($_POST['profileupdate'])) {

            $adminPassword = $_POST['adminPassword'];
            $anrede = $_POST['anrede'];
            $vorname = $_POST['vorname'];
            $nachname = $_POST['nachname'];
            $email = $_POST['email'];
            $newpassword1 = $_POST['newpassword1'];
            $newpassword2 = $_POST["newpassword2"];
            $newstatus = $_POST['userstatus'];

            $adminUsername = $_SESSION["name"];

            $sql = "SELECT * FROM users WHERE `user_name`='$adminUsername '";
            $db_check = $mysqli->query($sql);
            $adminData = mysqli_fetch_assoc($db_check);

            if (password_verify($adminPassword, $adminData['user_password'])) {
                if (($newpassword1 != "" && $newpassword2 == "") || ($newpassword1 == "" && $newpassword2 != "") || ($newpassword1 != "" && $newpassword2 != "")) {
                    if ($newpassword1 == $newpassword2) {
                        $options = array("cost" => 4);
                        $hashpwnew = password_hash($newpassword1, PASSWORD_BCRYPT, $options);

                        $qslUpdateProfile = "UPDATE profiles SET anrede = ?, first_name = ?, last_name = ?, email = ?
							WHERE fk_user_id = (SELECT user_id FROM users WHERE user_id = ?)";
                        $stmt2 = $mysqli->prepare($qslUpdateProfile);
                        $stmt2->bind_param('sssss', $anrede, $vorname, $nachname, $email, $user_id);

                        $sqlUpdateUser = "UPDATE users SET user_password = ?, user_status = ? WHERE user_id = ?;";
                        $stmt3 = $mysqli->prepare($sqlUpdateUser);
                        $stmt3->bind_param('sis', $hashpwnew, $newstatus, $user_id);

                        if (!$stmt2->execute()) {
                            echo "Error: " . $stmt2->error;
                            exit();
                        } else if (!$stmt3->execute()) {
                            echo "Error: " . $stmt3->error;
                            exit();
                        } else {
                            header('Location: index.php?site=profile-admin&id=' . $user_id);
                        }
                    } else {
                        $error = "Entered passwords do not match";
                    }
                } else {
                    $qslUpdateProfile = "UPDATE profiles SET anrede = ?, first_name = ?, last_name = ?, email = ? 
						WHERE fk_user_id = (SELECT user_id FROM users WHERE user_id = ?)";
                    $stmt2 = $mysqli->prepare($qslUpdateProfile);
                    $stmt2->bind_param('sssss', $anrede, $vorname, $nachname, $email, $user_id);

                    $sqlUpdateUser = "UPDATE users SET user_status = ? WHERE user_id = ?;";
                    $stmt3 = $mysqli->prepare($sqlUpdateUser);
                    $stmt3->bind_param('is', $newstatus, $user_id);

                    if (!$stmt2->execute()) {
                        echo "Error: " . $stmt2->error;
                        exit();
                    } else if (!$stmt3->execute()) {
                        echo "Error: " . $stmt3->error;
                        exit();
                    } else {
                        header('Location: index.php?site=profile-admin&id=' . $user_id);
                    }
                }
            } else {
                $error = "Entered password is incorrect.";
            }

            // if ($error != "") {
                // echo $error;
            // }
        }
        ?>


        <!--                        H       T       M       L                   F       O       R       M                        -->
        <form action="index.php?site=profile-update-admin&id=<?= $user_id ?>" method="POST" autocomplete="off">
            <div class="form-floating mb-2">
                <select class="form-select mb-2" id="userstatus" name="userstatus">
                    <option selected disabled>Wählen...</option>
                    <option <?php if ($oldUserStatus == 0) { ?> selected="true" <?php }; ?> value=0>Inaktiv</option>
                    <option <?php if ($oldUserStatus == 1) { ?> selected="true" <?php }; ?> value=1>Aktiv</option>
                </select>
                <label class="form-label" for="userstatus">Status</label>
            </div>

            <div class="form-floating mb-2">
                <select class="form-select mb-2" id="anrede" name="anrede">
                    <option selected disabled>Wählen...</option>
                    <option <?php if ($oldAnrede == 'M') { ?> selected="true" <?php }; ?> value="M">Herr</option>
                    <option <?php if ($oldAnrede == 'F') { ?> selected="true" <?php }; ?> value="F">Frau</option>
                </select>
                <label class="form-label" for="anrede">Anrede</label>
            </div>

            <div class="form-floating mb-2">
                <input class="form-control" id="vorname" type="text" name="vorname" placeholder="Vorname" value="<?php echo $oldFirstname; ?>" required />
                <label for="vorname">Vorname</label>
                <span class="form-text error">
                    <?php
                    if (isset($_POST['profileupdate'])) {
                        if (empty($vorname)) echo 'Bitte geben Sie Ihren Vornamen ein!';
                        else if (!preg_match('/^[a-zA-Z ]*$/', $vorname)) {
                            echo 'Überprüfen Sie Ihre Eingabe!';
                        } else $vornameValid = true;
                    }
                    ?>
                </span>
            </div>

            <div class="form-floating mb-2">
                <input class="form-control mb-2" id="nachname" type="text" name="nachname" placeholder="Nachname" value="<?php echo $oldLastname; ?>" required />
                <label class="form-label" for="nachname">Nachname</label>
                <span class="form-text error">
                    <?php
                    if (isset($_POST['profileupdate'])) {
                        if (empty($nachname)) echo 'Bitte geben Sie Ihren Nachnamen ein!';
                        else if (!preg_match('/^[a-zA-Z ]*$/', $nachname)) {
                            echo 'Überprüfen Sie Ihre Eingabe!';
                        } else $nachnameValid = true;
                    }
                    ?>
                </span>
            </div>

            <div class="form-floating mb-2">
                <input class="form-control mb-2" id="email" type="email" name="email" placeholder="E-Mail" value="<?php echo $oldEmail; ?>" required />
                <label class="form-label" for="email">E-Mail</label>
                <span class="form-text error">
                    <?php
                    if (isset($_POST['profileupdate'])) {
                        if (empty($email)) echo 'Bitte geben Sie Ihre E-Mail Adresse ein!';
                        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            echo "Bitte geben Sie eine gültige E-Mail Adresse ein!";
                        } else $emailValid = true;
                    }
                    ?>
                </span>
            </div>

            <div class="form-floating mb-2">
                <input class="form-control mb-2" id="username" type="text" name="username" placeholder="Username" value="<?php echo $oldUsername; ?>" disabled />
                <label class="form-label" for="username">Benutzername</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control mb-2" id="newpassword1" type="password" name="newpassword1" placeholder="" value="" minlength="8" />
                <label class="form-label" for="newpassword1">Neues Passwort</label>
                <span class="form-text error">
                    <?php
                    if (isset($_POST['profileupdate']) && !empty($newpassword1)) {
                        $uppercase = preg_match('@[A-Z]@', $newpassword1);
                        $lowercase = preg_match('@[a-z]@', $newpassword1);
                        $number = preg_match('@[0-9]@', $newpassword1);
                        $specialchars = preg_match('@[^\w]@', $newpassword1);
                        if (!$uppercase || !$lowercase || !$number || !$specialchars || strlen($newpassword1) < 8) {
                            echo 'Passwort muss mind. ein Großbuchstabe, eine Zahl und ein Sonderzeichen beinhalten!';
                        } else $passwordValid = true;
                    }
                    ?>
                </span>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control mb-2" id="newpassword2" type="password" name="newpassword2" placeholder="" value="" minlength="8" />
                <label class="form-label" for="newpassword2">Neues Passwort Wiederholung</label>
                <span class="form-text error">
                    <?php
                    if (isset($_POST['profileupdate'])) {
                        if (!empty($newpassword1)) {
                            if (empty($newpassword2)) echo 'Bitte wiederholen Sie das Passwort!';
                            else if ($newpassword2 != $newpassword1) echo 'Passwörter stimmen nicht überein!';
                            else $confirmValid = true;
                        }
                    }
                    ?>
                </span>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" id="adminPassword" type="password" name="adminPassword" placeholder="" value="" minlength="8" required />
                <label class="form-label" for="adminPassword">Admin Passwort *</label>
                <span class="form-text error">
                    <?php
                    if (isset($_POST['profileupdate'])) {
                        if ($error = "Entered password is incorrect.") {
                            echo "Entered password is incorrect.";
                        }
                    }
                    ?>
                </span>
                <div class="form-text">Für Änderungen von Userdaten wird das Passwort benötigt.</div>
            </div>

            <div class="row gx-2">
                <div class="col-12 col-sm-6 mb-2">
                    <a class="btn btn-lg btn-outline-secondary w-100" href="index.php?site=profile-admin&id=<?= $user_id ?>">Änderungen verwerfen</a>
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    <input class="btn btn-lg btn-primary w-100" type="submit" name="profileupdate" value="Daten ändern" />
                </div>
            </div>

        </form>
    </div>
</div>