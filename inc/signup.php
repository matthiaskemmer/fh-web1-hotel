<?php include 'dbaccess.php'; ?>
<div class="row justify-content-center">
	<div class="col-md-10 col-lg-8 col-xl-6">
		<h1>Registrierung</h1>

		<?php
		// General validation:
		$vorname = $nachname = $username = $email = $password = $confirm = "";
		$vornameValid = $nachnameValid = $usernameValid = $emailValid = $passwordValid = $confirmValid = "";
		if (isset($_POST['submit'])) {
			$vorname = test_input($_POST["vorname"]);
			$nachname = test_input($_POST["nachname"]);
			$username = test_input($_POST["username"]);
			$email = test_input($_POST["email"]);
			$password = test_input($_POST["password"]);
			$confirm = test_input($_POST["confirm"]);
		}

		function test_input($data)
		{
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		?>

		<!-- 						H		T		M		L					F		O		R		M						 -->
		<form action="index.php?site=signup" method="POST" autocomplete="off">
			<div class="form-floating mb-3">
				<select class="form-select mb-2" id="anrede" name="anrede" required>
					<option selected disabled>Wählen...</option>
					<option <?php if (isset($_POST['anrede']) && $_POST['anrede'] == 'M') { ?>selected="true" <?php }; ?> value="M">Herr</option>
					<option <?php if (isset($_POST['anrede']) && $_POST['anrede'] == 'F') { ?>selected="true" <?php }; ?> value="F">Frau</option>
				</select>
				<label class="form-label" for="anrede">Anrede
					<span class="error">*</span>
				</label>
				<span class="form-text error">
					<?php
					if (isset($_POST["submit"]) && !isset($_POST['anrede'])) {
						echo 'Bitte wählen Sie eine Anrede.';
					}
					?>
				</span>
			</div>

			<div class="form-floating mb-3">
				<input class="form-control mb-2" id="vorname" placeholder="Vorname" type="text" name="vorname" value="<?php if (isset($_POST["vorname"])) echo $_POST["vorname"] ?>" required />
				<label class="form-label" for="vorname">Vorname
					<span class="error">*</span>
				</label>
				<span class="form-text error">
					<?php
					if (isset($_POST['submit'])) {
						if (empty($vorname)) echo 'Bitte geben Sie Ihren Vornamen ein!';
						else if (!preg_match('/^[a-zA-Z ]*$/', $vorname)) {
							echo 'Überprüfen Sie Ihre Eingabe!';
						} else $vornameValid = true;
					}
					?>
				</span>
			</div>

			<div class="form-floating mb-3">
				<input class="form-control" id="nachname" placeholder="Nachname" type="text" name="nachname" value="<?php if (isset($_POST["nachname"])) echo $_POST["nachname"] ?>" required />

				<label class="form-label" for="nachname">Nachname
					<span class="error">*</span>
				</label>
				<span class="form-text error">
					<?php
					if (isset($_POST['submit'])) {
						if (empty($nachname)) echo 'Bitte geben Sie Ihren Nachnamen ein!';
						else if (!preg_match('/^[a-zA-Z ]*$/', $nachname)) {
							echo 'Überprüfen Sie Ihre Eingabe!';
						} else $nachnameValid = true;
					}
					?>
				</span>
			</div>

			<div class="form-floating mb-3">
				<input class="form-control mb-2" id="username" placeholder="Username" type="text" name="username" value="<?php if (isset($_POST["username"])) echo $_POST["username"] ?>" required />
				<label class="form-label" for="username">Benutzername
					<span class="error">*</span>
				</label>
				<span class="form-text error">
					<?php
					if (isset($_POST['submit'])) {
						$username = $_POST['username'];
						$sqlUsernameCheck = "SELECT user_name FROM users WHERE user_name = ?;";
						$stmtx = $mysqli->prepare($sqlUsernameCheck);
						$stmtx->bind_param("s", $username);
						$stmtx->execute();
						$stmtx->store_result();

						if (empty($username)) echo 'Bitte geben Sie einen Benutzernamen ein!';
						else if (!preg_match('/^\w{5,}$/', $username)) { // \w equals "[0-9A-Za-z_]"
							// valid username, alphanumeric & longer than or equals 5 chars
							echo 'Benutzername nicht gültig!';
						} else if ($stmtx->num_rows > 0) {
							echo 'Benutzername existiert bereits!';
						} else {
							$usernameValid = true;
						}
					}
					?>
				</span>
			</div>

			<div class="form-floating mb-3">
				<input class="form-control mb-2" id="email" placeholder="E-Mail" type="email" name="email" value="<?php if (isset($_POST["email"])) echo $_POST["email"] ?>" required />
				<label class="form-label" for="email">E-Mail
					<span class="error">*</span>
				</label>
				<span class="form-text error">
					<?php
					if (isset($_POST['submit'])) {
						if (empty($email)) echo 'Bitte geben Sie Ihre E-Mail Adresse ein!';
						else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
							echo "Bitte geben Sie eine gültige E-Mail Adresse ein!";
						} else $emailValid = true;
					}
					?>
				</span>
			</div>

			<div class="form-floating mb-3">
				<input class="form-control mb-2" id="password" type="password" name="password" placeholder="Passwort" minlength="8" required />
				<label class="form-label" for="password">Passwort
					<span class="error">*</span>
				</label>
				<span class="form-text error">
					<?php
					if (isset($_POST['submit'])) {
						$uppercase = preg_match('@[A-Z]@', $password);
						$lowercase = preg_match('@[a-z]@', $password);
						$number = preg_match('@[0-9]@', $password);
						$specialchars = preg_match('@[^\w]@', $password);
						if (empty($password)) echo 'Bitte geben Sie ein Passwort ein!';
						else if (!$uppercase || !$lowercase || !$number || !$specialchars || strlen($password) < 8) {
							echo 'Passwort muss mind. ein Großbuchstabe, eine Zahl und ein Sonderzeichen beinhalten!';
						} else $passwordValid = true;
					}
					?>
				</span>
			</div>

			<div class="form-floating mb-3">
				<input class="form-control mb-3" id="confirm" type="password" name="confirm" minlength="8" placeholder="Passwort" required />

				<label class="form-label" for="confirm">Passwort (Wiederholung)
					<span class="error">*</span>
				</label>
				<span class="form-text error">
					<?php
					if (isset($_POST['submit'])) {
						if (empty($confirm)) echo 'Bitte wiederholen Sie das Passwort!';
						else if ($confirm != $password) echo 'Passwörter stimmen nicht überein!';
						else $confirmValid = true;
					}
					?>
				</span>
			</div>
			<div class="d-grid gap-2">
				<input class="btn btn-lg btn-primary mb-3" type="submit" name="submit" value="Konto anlegen" />
			</div>
		</form>
	</div>
</div>

<!-- 					D	A	T	E	N	B	A	N	K				U	E	B	E	R	T	R	A	G	U	N	G		 -->
<?php
if (isset($_POST['submit'])) {
	if ($vornameValid && $nachnameValid && $usernameValid && $emailValid && $passwordValid && $confirmValid) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$options = array("cost" => 4);
		$hashPassword = password_hash($password, PASSWORD_BCRYPT, $options);
		$sql1 = "INSERT INTO `users`(`user_name`, `user_password`, `user_status`, `fk_role_id`) VALUES (?, ?, 1, 2)";
		$stmt = $mysqli->prepare($sql1);
		$stmt->bind_param("ss", $username, $hashPassword);
		if (!$stmt->execute()) {
			echo "Error 1: " . $stmt->error;
			exit();
		}
		$user_id = $mysqli->insert_id; // get the auto-incremented user_id value of the newly inserted user

		$anrede = $_POST['anrede'];
		$vorname = $_POST['vorname'];
		$nachname = $_POST['nachname'];
		$email = $_POST['email'];
		$sql2 = "INSERT INTO `profiles`(`anrede`, `first_name`, `last_name`, `email`, `fk_user_id`) VALUES (?,?,?,?,?)";
		$stmt2 = $mysqli->prepare($sql2);
		$stmt2->bind_param("ssssi", $anrede, $vorname, $nachname, $email, $user_id);
		if (!$stmt2->execute()) {
			echo "Error 2: " . $stmt2->error;
			exit();
		} else {
			echo "<script>location.assign('index.php?site=login')</script>";
			//header('Location: index.php?site=login');
		}

		$conn->close();
	}
}

?>