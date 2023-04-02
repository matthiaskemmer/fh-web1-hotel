<?php include 'dbaccess.php'; ?>
<div class="row justify-content-center mb-4">
  <div class="col-md-10 col-lg-8 col-xl-6">
    <h1>News</h1>
    <?php include "./inc/alert.php"; ?>
    <?php
    if (isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]) {
      echo '<a class="btn btn-primary float-end" href="./index.php?site=news-admin">Neue Nachricht anlegen</a>';
    }
    ?>
  </div>
</div>

<?php
$sql = "SELECT news.title, news.text, news.timestamp, images.path FROM `news` JOIN `images` ON news.fk_image_id = images.image_id ORDER BY news.timestamp DESC;";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($title, $text, $timestamp, $img_path);
if ($stmt->num_rows == 0) {
  $_SESSION["msg_info"] = "Es existieren noch keine News-Einträge.";
}
for ($i = 0; $i < $stmt->num_rows; $i++) {
  $stmt->fetch();
?>
  <div class="row justify-content-center">
    <div class="col-md-10 col-lg-8 col-xl-6">
      <div class="card shadow-lg mb-4">
        <img class="img-fluid rounded-top mb-3" src="<?=$img_path?>">
        <div class="px-3">
          <h3 class="text-center"><?=$title?></h3>
          <p><?=$text?></p>
          <p style="text-align: right"><i><?=date('d.m.Y', strtotime($timestamp))?></i></p>
        </div>
      </div>
    </div>
  </div>
  
<?php
}
?>