<?php include("require_user.php"); ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $date1 = strtotime($_POST["anreise"]);
  $date2 = strtotime($_POST["abreise"]);

  if ($date2 > $date1){
    $_SESSION["booking_date1"] = $_POST["anreise"];
    $_SESSION["booking_date2"] = $_POST["abreise"];
    $_SESSION["booking_num_people"] = $_POST["num_people"];
  
    if (isset($_POST["pets"])) {
      $_SESSION["booking_pets"] = "1";
    } else {
      $_SESSION["booking_pets"] = "0";
    }
  
    if (isset($_POST["breakfast"])) {
      $_SESSION["booking_breakfast"] = "1";
    } else {
      $_SESSION["booking_breakfast"] = "0";
    }
  
    if (isset($_POST["parking"])) {
      $_SESSION["booking_parking"] = "1";
    } else {
      $_SESSION["booking_parking"] = "0";
    }

    header("Location: index.php?site=booking-rooms");
  }

}
?>
<div class="row justify-content-center mb-4">
  <div class="col-md-10 col-lg-8 col-xl-6">
    <h1>Ihre Reisedaten </h1>
    <form action="" method="POST">
      <div class="form-floating mb-3">
        <input type="date" class="form-control" id="AnreiseDatum" name="anreise" min="<?php echo date('Y-m-d'); ?>" placeholder="">
        <label for="AnreiseDatum">Anreisedatum</label>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
          if (empty($_POST["anreise"])) {
            echo '<span class="form-text error">Bitte wählen Sie ein Anreisedatum.</span>';
          }
        }
        ?>
      </div>
      <div class="form-floating mb-3">
        <input type="date" name="abreise" min="<?php echo date('Y-m-d', strtotime("+1 day")); ?>" class="form-control" id="AbreiseDatum" placeholder="">
        <label for="AbreiseDatum">Abreisedatum</label>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
          if (empty($_POST["abreise"])) {
            echo '<span class="form-text error">Bitte wählen Sie ein Abreisedatum.</span>';
          } else {
            if (!($date2 > $date1)) {
              echo '<span class="form-text error">Ungültiges Abreisedatum.</span>';
            }
          }
        }
        ?>
      </div>
      <div class="form-floating mb-3">
        <select class="form-select" name="num_people" id="AnzahlPersonen">
          <option value="" selected>Anzahl auswählen...</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
        </select>
        <label for="AnzahlPersonen">Personen</label>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
          if (empty($_POST["num_people"])) {
            echo '<span class="form-text error">Bitte geben Sie die Anzahl der Personen an.</span>';
          }
        }
        ?>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="haustiereErlaubt" name="pets" value="1" />
        <label for="haustiereErlaubt">Haustiere erlaubt</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="parking" value="1" id="Parkplatz" />
        <label class="form-check-label" for="Parkplatz">
          Parkplatz (10€&#8239;/&#8239;Tag)
        </label>
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="breakfast" value="1" id="Fruehstueck" />
        <label class="form-check-label" for="Fruehstueck">
          Frühstück (15€&#8239;/&#8239;Person)
        </label>
      </div>
      <div>
        <input class="btn btn-primary" type="submit" name="submit" value="Suchen" />
      </div>
    </form>
  </div>
</div>
