<?php include("require_user.php"); ?>
<?php include 'dbaccess.php'; ?>
<div class="row justify-content-center mb-4">
  <div class="col-md-10 col-lg-8 col-xl-6">
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $_SESSION["sel_room_id"] = $_POST["sel_room_id"];

      $query = "INSERT INTO bookings (num_persons, arrival_date, depature_date, parking, breakfast, fk_room_id, fk_user_id, fk_booking_status) VALUES (?, ?, ?, ?, ?, ?, ?, 1);";
      $stmt2 = $mysqli->prepare($query);
      $stmt2->bind_param('sssssss', $_SESSION["booking_num_people"], $_SESSION["booking_date1"], $_SESSION["booking_date2"], $_SESSION["booking_parking"], $_SESSION["booking_breakfast"], $_SESSION["sel_room_id"], $_SESSION["user_id"]);
      $stmt2->execute();

      header("Location: index.php?site=booking-list-guest");
    }

    if ($_SESSION["booking_pets"] == "1") {
      $sql = "SELECT b.booking_id, b.arrival_date, b.depature_date, r.number, r.num_beds, r.pets_allowed, r.room_id FROM rooms r LEFT JOIN bookings b ON r.room_id = b.fk_room_id WHERE r.num_beds >= ? AND r.pets_allowed = 1;";
    } else {
      $sql = "SELECT b.booking_id, b.arrival_date, b.depature_date, r.number, r.num_beds, r.pets_allowed, r.room_id FROM rooms r LEFT JOIN bookings b ON r.room_id = b.fk_room_id WHERE r.num_beds >= ?;";
    }

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $_SESSION["booking_num_people"]);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($booking_id, $arrival_date, $depature_date, $room_number, $room_bed_num, $room_pets, $room_id);

    if ($stmt->num_rows == 0) {
      $_SESSION["msg_info"] = "Es existieren leider keinen freien Zimmer für die angegebenen Suchparameter.";
    }
    ?>
    <h1 class="mb-md-4">Zimmer buchen </h1>
    <?php include 'alert.php'; ?>
    <form action="index.php?site=booking-rooms" method="post">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        <?php
        for ($i = 0; $i < $stmt->num_rows; $i++) {
          $stmt->fetch();

          // Check room availability
          $a1 = strtotime($_SESSION["booking_date1"]);
          $a2 = strtotime($_SESSION["booking_date2"]);
          $b1 = strtotime($arrival_date);
          $b2 = strtotime($depature_date);
          if (!($b1 > $a2 || $a1 > $b2 || $a2 < $a1 || $b2 < $b1)){
            continue;
          }
        ?>
          <div class="col d-flex align-items-stretch">
            <div class="card mb-4 shadow">
              <div class="ratio ratio-16x9">
                <img src="./res/images/hotel0<?= rand(1, 5); ?>.jpg" class="card-img-top" alt="...">
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title">Zimmer <?= $room_number ?></h5>
                  <input type="text" name="sel_room_id" value="<?= $room_id ?>" hidden>
                  <span class="badge rounded-pill text-bg-success">Verfügbar</span>
                </div>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <span class="text-success">
                  <?php
                  if ($room_pets == "1") {
                    echo "Haustiere erlaubt";
                  }
                  ?>
                </span>
              </div>
              <div class="card-footer text-center bg-white border-top-0 p-3">
                <input class="btn btn-primary w-50" type="submit" name="submit" value="buchen" />
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </form>

  </div>
</div>