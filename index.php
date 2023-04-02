<?php
session_start();
$site = @$_GET['site'];
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/png" href="./res/images/hotel-logo.png">
  <title><?php echo $site; ?> | Hotel Name</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link rel="stylesheet" href="./res/css/styles.css">
</head>

<body>

  <div id="page-container">
    <div id="content-wrap">
      <?php include "./inc/navigation.php"; ?>
      <div class="container-fluid">
        <?php
        if ($site == '') {
          header('Location: index.php?site=home');
        }

        switch ($site) {
          case 'home':
            include 'inc/home.php';
            break;
          case 'login':
            include 'inc/login.php';
            break;
          case 'logout':
            include 'inc/logout.php';
            break;
          case 'signup':
            include 'inc/signup.php';
            break;
          case 'news':
            include 'inc/news.php';
            break;
          case 'news-admin':
            include 'inc/news-admin.php';
            break;
          case 'impressum':
            include 'inc/impressum.php';
            break;
          case 'help':
            include 'inc/help.php';
            break;
          case 'profile':
            include 'inc/profile.php';
            break;
          case 'profile_update':
            include 'inc/profile_update.php';
            break;
          case 'profile-admin':
            include 'inc/profile-admin.php';
            break;
            case 'profile-update-admin':
              include 'inc/profile-update-admin.php';
              break;
          case 'booking':
            include 'inc/booking.php';
            break;
          case 'booking-list-guest':
            include 'inc/booking-list-guest.php';
            break;
          case 'booking-details':
            include 'inc/booking-details.php';
            break;
          case 'booking-details-admin':
            include 'inc/booking-details-admin.php';
            break;
          case 'booking-rooms':
            include 'inc/booking-rooms.php';
            break;
          case 'guests-admin':
            include 'inc/guests-admin.php';
            break;
          case 'room-admin':
            include 'inc/room-admin.php';
            break;
        }
        ?>
      </div>
    </div>
    <footer id="footer"><?php include "./inc/footer.php"; ?></footer>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

</body>

</html>