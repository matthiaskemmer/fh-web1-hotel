<?php include 'dbaccess.php'; ?>
<div class="row justify-content-center">
  <div class="col col-sm-6 col-md-4 col-xl-3">
    <?php include "./inc/alert.php"; ?>
    <?php
    // Check form data
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      if (isset($_POST["name"]) && isset($_POST["password"])) {

        // Load user data
        $sql = "SELECT user_id, user_password, user_status, fk_role_id FROM `users` WHERE `user_name` = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $_POST["name"]);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $user_pwd, $user_status, $user_role);
        $stmt->fetch();

        // Incorrect username
        if ($stmt->num_rows == 0) {
          $_SESSION["msg_error"] = "Anmeldung fehlgeschlagen! Bitte überprüfen Sie Ihre Anmeldedaten.";
          header('Location: index.php?site=login');
          return;
        }

        // Inactive user
        if ($user_status == 0) {
          $_SESSION["msg_error"] = "Anmeldung fehlgeschlagen! Inaktiver User.";
          header('Location: index.php?site=login');
          return;
        }

        // Compare the inputted password with the hashed password
        if (password_verify($_POST["password"], $user_pwd) == false) {
          $_SESSION["msg_error"] = "Anmeldung fehlgeschlagen! Bitte überprüfen Sie Ihre Anmeldedaten.";
          header('Location: index.php?site=login');
          return;
        }

        // Set session
        $_SESSION["name"] = $_POST["name"];
        $_SESSION['user_id'] = $id;
        $_SESSION["isadmin"] = False;
        $_SESSION['user_role'] = $user_role;
        $_SESSION["isadmin"] = False;
        $_SESSION["isuser"] = False;

        if ($_SESSION["user_role"] == 1) {
          $_SESSION["isadmin"] = True;
        }
        if ($_SESSION["user_role"] == 2) {
          $_SESSION["isuser"] = True;
        }

        $_SESSION["msg_info"] = "Willkommen zurück, " . $_SESSION["name"];
        header('Location: index.php?site=home');
      }
    }

    ?>
    <h1 class="mb-4">Login</h1>
    <form action="" method="POST" autocomplete="off">
      <div class="form-floating mb-3">
        <input type="text" class="form-control" name="name" id="name" placeholder="name@example.com" required>
        <label for="name">Benutzername</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
        <label for="floatingPassword">Passwort</label>
      </div>
      <div class="d-grid gap-2">
        <input class="btn btn-lg btn-primary mb-3" type="submit" name="submit" value="Anmelden" />
      </div>
    </form>
    <p class="text-center">Wenn Sie noch kein Konto haben, dann können Sie <a href="index.php?site=signup">hier</a> eines anlegen.</p>
  </div>
</div>