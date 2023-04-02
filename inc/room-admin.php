<?php include 'dbaccess.php'; ?>
<?php
// Handle status url parameter
if (!isset($_GET["status"])){
  header('Location: index.php?site=room-admin&status=0');
}

if ($_GET["status"] != "0" && isset($_GET["id"])) {
  $display_status = $_GET["status"];
  $sql = "SELECT b.booking_id, DATE_FORMAT(b.arrival_date,'%d.%m.%Y') as arrival_date, DATE_FORMAT(b.depature_date,'%d.%m.%Y') as depature_date, r.number, p.last_name, p.first_name, s.name, u.user_id FROM `bookings` b JOIN rooms r ON b.fk_room_id = r.room_id JOIN booking_status s ON b.fk_booking_status = s.status_id JOIN users u ON b.fk_user_id = u.user_id JOIN profiles p ON u.user_id = p.fk_user_id WHERE u.user_id = ? AND s.status_id = ?";
} else if ($_GET["status"] != "0" && !isset($_GET["id"])) {
  $display_status = $_GET["status"];
  $sql = "SELECT b.booking_id, DATE_FORMAT(b.arrival_date,'%d.%m.%Y') as arrival_date, DATE_FORMAT(b.depature_date,'%d.%m.%Y') as depature_date, r.number, p.last_name, p.first_name, s.name, u.user_id FROM `bookings` b JOIN rooms r ON b.fk_room_id = r.room_id JOIN booking_status s ON b.fk_booking_status = s.status_id JOIN users u ON b.fk_user_id = u.user_id JOIN profiles p ON u.user_id = p.fk_user_id WHERE s.status_id = ?";
} else if ($_GET["status"] == "0" && isset($_GET["id"])) {
  $display_status = "0";
  $sql = "SELECT b.booking_id, DATE_FORMAT(b.arrival_date,'%d.%m.%Y') as arrival_date, DATE_FORMAT(b.depature_date,'%d.%m.%Y') as depature_date, r.number, p.last_name, p.first_name, s.name, u.user_id FROM `bookings` b JOIN rooms r ON b.fk_room_id = r.room_id JOIN booking_status s ON b.fk_booking_status = s.status_id JOIN users u ON b.fk_user_id = u.user_id JOIN profiles p ON u.user_id = p.fk_user_id WHERE u.user_id = ?";
} elseif ($_GET["status"] == "0" && !isset($_GET["id"])) {
  $display_status = "0";
  $sql = "SELECT b.booking_id, DATE_FORMAT(b.arrival_date,'%d.%m.%Y') as arrival_date, DATE_FORMAT(b.depature_date,'%d.%m.%Y') as depature_date, r.number, p.last_name, p.first_name, s.name, u.user_id FROM `bookings` b JOIN rooms r ON b.fk_room_id = r.room_id JOIN booking_status s ON b.fk_booking_status = s.status_id JOIN users u ON b.fk_user_id = u.user_id JOIN profiles p ON u.user_id = p.fk_user_id";
}

// Load data
$stmt = $mysqli->prepare($sql);
if ($_GET["status"] != "0" && isset($_GET["id"])){
  $stmt->bind_param('ss', $_GET["id"], $display_status);
} else if ($_GET["status"] != "0" && !isset($_GET["id"])) {
  $stmt->bind_param('s', $display_status);
} else if ($_GET["status"] == "0" && isset($_GET["id"])) {
  $stmt->bind_param('s', $_GET["id"]);
} 
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($booking_id, $arrival_date, $depature_date, $room_number, $last_name, $first_name, $booking_status, $user_id);

if ($stmt->num_rows == 0) {
  $_SESSION["msg_info"] = "Es wurden keine Reservierungen gefunden.";
}
?>
<div class="row justify-content-center mb-4">
  <div class="col-md-10 col-lg-8 col-xl-6">
    <?php include("require_admin.php"); ?>
    <?php include("alert.php"); ?>
    <h1 class="mb-md-4">Reservierungsverwaltung</h1>

    <div class="btn-group mb-4">
      <a class="btn btn-outline-secondary <?php if ($display_status == "0") { echo "active"; }; ?>" href="index.php?site=room-admin&status=0<?php if (isset($_GET["id"])) { echo "&id=" . $_GET["id"]; } ?>">Alle</a>
      <a class="btn btn-outline-secondary <?php if ($display_status == "1") { echo "active"; }; ?>" href="index.php?site=room-admin&status=1<?php if (isset($_GET["id"])) { echo "&id=" . $_GET["id"]; } ?>">Neu</a>
      <a class="btn btn-outline-secondary <?php if ($display_status == "2") { echo "active"; }; ?>" href="index.php?site=room-admin&status=2<?php if (isset($_GET["id"])) { echo "&id=" . $_GET["id"]; } ?>">Bestätigt</a>
      <a class="btn btn-outline-secondary <?php if ($display_status == "3") { echo "active"; }; ?>" href="index.php?site=room-admin&status=3<?php if (isset($_GET["id"])) { echo "&id=" . $_GET["id"]; } ?>">Storniert</a>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-hover table-bordered">
        <thead class="table-dark text-center">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Zimmer</th>
            <th scope="col">Anreise</th>
            <th scope="col">Abreise</th>
            <th scope="col">Status</th>
            <th scope="col">Buchung</th>
          </tr>
        </thead>
        <tbody>
          <?php
          for ($i = 0; $i < $stmt->num_rows; $i++) {
            $stmt->fetch();
          ?>
            <tr class="text-center">
              <th class="text-end"><?= $i+1 ?></th>
              <td><?= $first_name ?> <span class="text-decoration-underline"><?= $last_name ?></span></td>
              <td><?= $room_number ?></td>
              <td><?= $arrival_date ?></td>
              <td><?= $depature_date ?></td>
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
              <td>
                <a class="btn btn-secondary btn-sm" href="index.php?site=booking-details-admin&id=<?= $booking_id ?>">Anzeigen</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>