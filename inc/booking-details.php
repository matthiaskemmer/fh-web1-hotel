<?php include 'dbaccess.php'; ?>
<?php
// Missing id parameter
if (!isset($_GET["id"])) {
  $_SESSION["msg_error"] = "Buchung konnte nicht gefunden werden.";
  header('Location: index.php?site=home');
  return;
}

// Load data
$sql = "SELECT b.booking_id, DATE_FORMAT(b.arrival_date,'%d.%m.%Y') as arrival_date, DATE_FORMAT(b.depature_date,'%d.%m.%Y') as depature_date, b.parking, b.breakfast, r.number, r.pets_allowed, p.last_name, p.first_name, s.name, u.user_id FROM `bookings` b JOIN rooms r ON b.fk_room_id = r.room_id JOIN booking_status s ON b.fk_booking_status = s.status_id JOIN users u ON b.fk_user_id = u.user_id JOIN profiles p ON u.user_id = p.fk_user_id WHERE b.booking_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $_GET["id"]);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($booking_id, $arrival_date, $depature_date, $parking, $breakfast, $room_number, $pets_allowed, $last_name, $first_name, $booking_status, $user_id);
$stmt->fetch();

if ($stmt->num_rows == 0) {
  $_SESSION["msg_error"] = "Buchung konnte nicht gefunden werden.";
  header('Location: index.php?site=home');
  return;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $sql_new = "UPDATE bookings SET fk_booking_status = ? WHERE booking_id = ?;";
  $stmt2 = $mysqli->prepare($sql_new);
  $stmt2->bind_param('ii', $_POST["bstatus"], $_GET["id"]);
  $stmt2->execute();
  $_SESSION["msg_info"] = "Buchung wurde geändert.";
  header('Location: index.php?site=room-admin&id=' . $user_id);
}
?>
<div class="row justify-content-center mb-4">
  <div class="col-md-10 col-lg-8 col-xl-6">
    <?php include("require_user.php"); ?>
    <?php include("alert.php"); ?>
    <h1 class="text-center mb-md-4">Buchungsdetails</h1>
    <form action="" method="POST" class="mb-4">
      <div class="row justify-content-center">
        <div class="table-responsive col col-sm-6">
          <table class="table table-borderless">
            <tbody>
              <tr class="text-start">
                <th scope="row">Buchungsnr.</th>
                <td><?= $booking_id ?></td>
              </tr>
              <tr class="text-start">
                <th scope="row">Name</th>
                <td><?= $first_name ?> <span class="text-decoration-underline"><?= $last_name ?></span></td>
              </tr>
              <tr class="text-start">
                <th scope="row">Zimmernr.</th>
                <td>#<?= $room_number ?></td>
              </tr>
              <tr class="text-start">
                <th scope="row">Ankunft</th>
                <td><?= $arrival_date ?></td>
              </tr>
              <tr class="text-start">
                <th scope="row">Abreise</th>
                <td><?= $depature_date ?></td>
              </tr>
              <tr class="text-start">
                <th scope="row">Haustiere</th>
                <td><?php if ($pets_allowed == 1) { ?> Ja <?php } else { ?> Nein <?php }; ?></td>
              </tr>
              <tr class="text-start">
                <th scope="row">Frühstück</th>
                <td><?php if ($breakfast == 1) { ?> Ja <?php } else { ?> Nein <?php }; ?></td>
              </tr>
              <tr class="text-start">
                <th scope="row">Parkplatz</th>
                <td><?php if ($parking == 1) { ?> Ja <?php } else { ?> Nein <?php }; ?></td>
              </tr>
              <tr class="text-start">
                <th scope="row">Status</th>
                <td>
                <?php if ($booking_status == "Neu") {
                  echo '<span class="badge rounded-pill text-bg-warning">' . $booking_status . '</span>';
                } else if ($booking_status == "Bestätigt") {
                  echo '<span class="badge rounded-pill text-bg-success">' . $booking_status . '</span>';
                } else if ($booking_status == "Storniert") {
                  echo '<span class="badge rounded-pill text-bg-danger">' . $booking_status . '</span>';
                }
                ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </form>
    <div class="row justify-content-center px-3">
        <a class="btn btn-primary col col-md-6" href="index.php?site=booking-list-guest">Meine Buchungen</a>
      </div>
  </div>
</div>