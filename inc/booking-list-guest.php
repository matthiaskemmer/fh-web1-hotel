<?php include 'dbaccess.php'; ?>
<?php include("require_user.php"); ?>
<?php
// get bookings from DB
$sql = "SELECT @rownum := @rownum + 1 AS 'Buchungsnummer', rooms.number, DATE_FORMAT(bookings.arrival_date,'%d.%m.%Y') as arrival_date, DATE_FORMAT(bookings.depature_date,'%d.%m.%Y') as depature_date, bookings.fk_booking_status, bookings.booking_id
FROM rooms
JOIN bookings ON rooms.room_id = bookings.fk_room_id
, (SELECT @rownum := 0) r 
WHERE bookings.fk_user_id = ?
ORDER BY bookings.arrival_date ASC;
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($buchungNr, $room_id, $arrival_date, $depature_date, $booking_status, $booking_id);

if ($stmt->num_rows == 0) {
  $_SESSION["msg_info"] = "Es existieren noch keine Buchungen.";
}
?>

<div class="row justify-content-center mb-4">
  <div class="col-md-10 col-lg-8 col-xl-6">
    <?php include("alert.php"); ?>
    <h1 class="mb-md-4">Meine Buchungen</h1>

    <div class="table-responsive">
      <table class="table table-striped table-hover table-bordered">
        <thead class="table-dark">
          <tr class="text-center">
            <th scope="col">#</th>
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
              <th><?= $i+1 ?></th>
              <td><?= $room_id ?></td>
              <td><?= $arrival_date ?></td>
              <td><?= $depature_date ?></td>
              <td>
                <?php if ($booking_status == 1) {
                  echo '<span class="badge rounded-pill text-bg-warning">Neu</span>';
                } else if ($booking_status == 2) {
                  echo '<span class="badge rounded-pill text-bg-success">Bestätigt</span>';
                } else if ($booking_status == 3) {
                  echo '<span class="badge rounded-pill text-bg-danger">Storniert</span>';
                }
                ?>
              </td>
              <td>
                <a class="btn btn-secondary btn-sm" href="index.php?site=booking-details&id=<?= $booking_id ?>">Anzeigen</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>