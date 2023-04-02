<nav class="navbar navbar-expand-md bg-dark navbar-dark shadow-lg px-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php?site=home">
      <img src="./res/images/hotel-logo.png" alt="hotel-logo" width="30" height="30">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php?site=news">News</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?site=help">Hilfe</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?site=impressum">Impressum</a>
        </li>
        <?php
        if (isset($_SESSION["name"]) && !$_SESSION["isadmin"]) {
          echo '<li class="nav-item"><a class="nav-link" href="index.php?site=profile">Profil</a></li>';
          echo '<li class="nav-item"><a class="nav-link" href="index.php?site=booking">Buchung</a></li>';
          echo '<li class="nav-item"><a class="nav-link" href="index.php?site=booking-list-guest">Meine Buchungen</a></li>';
        }
        if (isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]) {
          echo '<li class="nav-item"><a class="nav-link" href="index.php?site=room-admin&status=0">Reservierungen</a></li>';
          echo '<li class="nav-item"><a class="nav-link" href="index.php?site=guests-admin">User</a></li>';
        }
        ?>
      </ul>
      <span class="ms-auto row align-items-center">
        <?php
        if (isset($_SESSION["name"])) {
          echo '<div class="col-sm text-secondary text-white fw-bold mt-2 mb-3 me-3 px-0">@'. $_SESSION["name"] . '</div>';          echo '<a class="col-sm btn btn-outline-secondary me-2 mb-2" href="index.php?site=logout">Logout</a>';
        } else {
          echo '<a class="col-sm btn btn-outline-secondary me-2 mb-2" href="index.php?site=login">Login</a>';
          echo '<a class="col-sm btn btn-primary me-2 mb-2" href="index.php?site=signup">Signup</a>';
        }
        ?>
        </span>
    </div>
  </div>
</nav>