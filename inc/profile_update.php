<?php include 'dbaccess.php'; ?>
<?php include("require_user.php"); ?>

<?php
$findresult = "SELECT profiles.anrede, profiles.first_name, profiles.last_name, profiles.email, users.user_name, users.user_password
FROM profiles
JOIN users ON profiles.fk_user_id = users.user_id
WHERE users.user_name = ?";
$stmt = $mysqli->prepare($findresult);

// Check if the prepare statement failed
if (!$stmt) {
	echo "Error: " . $mysqli->error;
	exit();
}
$stmt->bind_param('s', $_SESSION["name"]);

// Check if the execute statement failed
if (!$stmt->execute()) {
	echo "Error: " . $stmt->error;
	exit();
}
$stmt->store_result();
$stmt->bind_result($anredeCurrent, $firstnameCurrent, $lastnameCurrent, $emailCurrent, $usernameCurrent, $userpasswordCurrent);

// Check if the fetch statement failed
if (!$stmt->fetch()) {
	echo "Error: " . $stmt->error;
	exit();
}
?>

<div class="row justify-content-center mb-4">
	<div class="col-md-10 col-lg-8 col-xl-6">
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

			$passwordcurrent = $_POST['passwordcurrent'];
			$anrede = $_POST['anrede'];
			$vorname = $_POST['vorname'];
			$nachname = $_POST['nachname'];
			$email = $_POST['email'];
			$newpassword1 = $_POST['newpassword1'];
			$newpassword2 = $_POST["newpassword2"];

			$sql = "SELECT * FROM users WHERE `user_name`='$usernameCurrent'";
			$db_check = $mysqli->query($sql);
			$user = mysqli_fetch_assoc($db_check);

			if (password_verify($passwordcurrent, $user['user_password'])) {
				if (($newpassword1 != "" && $newpassword2 == "") || ($newpassword1 == "" && $newpassword2 != "") || ($newpassword1 != "" && $newpassword2 != "")) {
					if ($newpassword1 == $newpassword2) {
						$options = array("cost" => 4);
						$hashpwnew = password_hash($newpassword1, PASSWORD_BCRYPT, $options);

						$qslUpdateProfile = "UPDATE profiles SET anrede = ?, first_name = ?, last_name = ?, email = ?
							WHERE fk_user_id = (SELECT user_id FROM users WHERE user_name = ?)";
						$stmt2 = $mysqli->prepare($qslUpdateProfile);
						$stmt2->bind_param('sssss', $anrede, $vorname, $nachname, $email, $usernameCurrent);

						$sqlUpdatePW = "UPDATE users SET user_password = ? WHERE user_name = ?;";
						$stmt3 = $mysqli->prepare($sqlUpdatePW);
						$stmt3->bind_param('ss', $hashpwnew, $usernameCurrent);

						if (!$stmt2->execute()) {
							echo "Error: " . $stmt2->error;
							exit();
						} else if (!$stmt3->execute()) {
							echo "Error: " . $stmt3->error;
							exit();
						} else {
							header('Location: index.php?site=profile');
						}
					} else {
						$_SESSION["msg_error"] = "Entered passwords do not match";
					}
				} else {
					$qslUpdateProfile = "UPDATE profiles SET anrede = ?, first_name = ?, last_name = ?, email = ? 
						WHERE fk_user_id = (SELECT user_id FROM users WHERE user_name = ?)";
					$stmt2 = $mysqli->prepare($qslUpdateProfile);
					$stmt2->bind_param('sssss', $anrede, $vorname, $nachname, $email, $usernameCurrent);

					if (!$stmt2->execute()) {
						echo "Error: " . $stmt2->error;
						exit();
					} else {
						$_SESSION["msg_info"] = "Daten erfolgreich geändert.";
						header('Location: index.php?site=profile');
					}
				}
			} else {
				$error = "Entered password is incorrect.";
			}

			// if ($error != "") {
			//	echo $error;
			// }
		}
		?>


		<!--                        H       T       M       L                   F       O       R       M                        -->
		<form action="index.php?site=profile_update" method="POST" autocomplete="off">
			<div class="form-floating mb-2">
				<select class="form-select mb-2" id="anrede" name="anrede">
					<option selected disabled>Wählen...</option>
					<option <?php if ($anredeCurrent == 'M') { ?> selected="true" <?php }; ?> value="M">Herr</option>
					<option <?php if ($anredeCurrent == 'F') { ?> selected="true" <?php }; ?> value="F">Frau</option>
				</select>
				<label class="form-label" for="anrede">Anrede</label>
			</div>
			<div class="form-floating mb-2">
				<input class="form-control" id="vorname" type="text" name="vorname" placeholder="Vorname" value="<?php echo $firstnameCurrent; ?>" required />
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
				<input class="form-control mb-2" id="nachname" type="text" name="nachname" placeholder="Nachname" value="<?php echo $lastnameCurrent; ?>" required />
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
				<input class="form-control mb-2" id="email" type="email" name="email" placeholder="E-Mail" value="<?php echo $emailCurrent; ?>" required />
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
				<input class="form-control mb-2" id="username" type="text" name="username" placeholder="Username" value="<?php echo $usernameCurrent; ?>" disabled />
				<label class="form-label" for="username">Benutzername</label>
			</div>
			<div class="form-floating mb-3">
				<input class="form-control mb-2" id="newpassword1" type="password" name="newpassword1" placeholder="" value="" minlength="8" />
				<label class="form-label" for="password1">Neues Passwort</label>
				<span class="form-text error">
					<?php
					if (isset($_POST['profileupdate'])) {
						$uppercase = preg_match('@[A-Z]@', $newpassword1);
						$lowercase = preg_match('@[a-z]@', $newpassword1);
						$number = preg_match('@[0-9]@', $newpassword1);
						$specialchars = preg_match('@[^\w]@', $newpassword1);
						if (!empty($newpassword1)) {
							if (!$uppercase || !$lowercase || !$number || !$specialchars || strlen($newpassword1) < 8) {
								echo 'Passwort muss mind. ein Großbuchstabe, eine Zahl und ein Sonderzeichen beinhalten!';
							} else $passwordValid = true;
						}
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
				<input class="form-control" id="passwordcurrent" type="password" name="passwordcurrent" placeholder="" value="" minlength="8" required />
				<label class="form-label" for="password1">Aktuelles Passwort</label>
				<span class="form-text error">
					<?php
					if (isset($_POST['profileupdate'])) {
						if ($error = "Entered password is incorrect.") {
							echo "Entered password is incorrect.";
						}
					}
					?>
				</span>
				<div class="form-text">Für Profiländerungen wird das aktuelle Passwort benötigt.</div>
			</div>
			<div class="row gx-2">
				<div class="col-12 col-sm-6 mb-2">
					<a class="btn btn-lg btn-outline-secondary w-100" href="index.php?site=profile">Änderungen verwerfen</a>
				</div>
				<div class="col-12 col-sm-6 mb-3">
					<input class="btn btn-lg btn-primary w-100" type="submit" name="profileupdate" value="Daten ändern" />
				</div>
			</div>
		</form>
	</div>
</div>