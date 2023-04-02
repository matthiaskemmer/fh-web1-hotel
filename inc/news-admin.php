<?php include 'dbaccess.php'; ?>
<?php include("require_admin.php"); ?>
<?php
if (isset($_FILES["file"])) {
  if ($_FILES["file"]["type"] != "image/jpeg") {
    $_SESSION["msg_error"] = "Beitrag konnte nicht erstellt werden. Fehler beim Bild-Upload.";
    header('Location: index.php?site=news-admin');
    return 0;
  }

  $org_file_name = $_FILES["file"]["name"];
  $file_name = $_FILES["file"]["tmp_name"];
  $thumb = imagecreatetruecolor(720, 480);
  $source = imagecreatefromjpeg($file_name);
  $imgsize = getimagesize($file_name);
  imagecopyresized($thumb, $source, 0, 0, 0, 0, 720, 480, $imgsize[0], $imgsize[1]);

  $destination_path = $_SERVER["DOCUMENT_ROOT"] . "/webtech/uploads/news/" . $org_file_name;
  imagejpeg($thumb, $destination_path);
}

// DATABASE:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $new_path = "/webtech/uploads/news/" . $org_file_name;

  // Save uploaded and resized image to database:
  $sql1 = "INSERT INTO images (images.path, images.name) VALUES (?, ?);";
  $stmt = $mysqli->prepare($sql1);
  $stmt->bind_param('ss', $new_path, $org_file_name);
  $stmt->execute();

  // Get image_id from database:
  $sql3 = "SELECT images.image_id FROM images WHERE images.path = ?;";
  $stmt2 = $mysqli->prepare($sql3);
  $stmt2->bind_param('s', $new_path);
  $stmt2->execute();
  $stmt2->store_result();
  $stmt2->bind_result($image_id);
  $stmt2->fetch();

  // Save news data to database:
  if(isset($_POST["title"]) && isset($_POST["desc"])){
    $timestamp = date("Y-m-d H:i:s");
    $sql2 = "INSERT INTO news (news.title, news.text, news.timestamp, news.fk_image_id) VALUES (?, ?, ?, ?);";
    $stmt = $mysqli->prepare($sql2);
    $stmt->bind_param('sssi', $_POST["title"], $_POST["desc"], $timestamp, $image_id);
    $stmt->execute();
  }
  header('Location: index.php?site=news');
}
?>

<div class="row justify-content-center mb-4">
  <div class="col-md-10 col-lg-8 col-xl-6">
    <h1>News anlegen</h1>
    <?php include "./inc/alert.php"; ?>
    <form action="index.php?site=news-admin" method="post" enctype="multipart/form-data">
      <div class="mb-2">
        <div class="form-floating">
          <input type="text" class="form-control" name="title" id="title" value="<?php if (isset($_POST["title"])) echo $_POST["title"] ?>" placeholder="." required>
          <label class="form-label" for="title">Titel</label>
        </div>
      </div>
      <div class="mb-2">
        <div class="form-floating">
          <textarea class="form-control" name="desc" id="desc" value="<?php if (isset($_POST["desc"])) echo $_POST["desc"] ?>" placeholder="." required style="height: 200px"></textarea>
          <label class="form-label" for="desc">Schreiben Sie Ihre Nachricht.</label>
        </div>
      </div>
      <input class="form-control mb-4" type="file" name="file" id="file" accept="image/*" placeholder=".">

      <div>
        <input class="btn btn-outline-secondary" type="reset" value="Löschen" onclick="return confirm('Wollen Sie Ihre Nachricht endgültig löschen?');"></button>
        <input class="btn btn-primary" type="submit" value="Hochladen"></button>
      </div>
    </form>

  </div>
</div>