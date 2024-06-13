<nav class="navbar navbar-expand-lg nav">
  <div class="container-fluid">
    <a class=" text-white text-decoration-none h4 ms-3" href="/index.php">Home</a>
    <div class="navbar-nav">
        <?php if(!isset($_SESSION['user_info'])): ?>
                <a href="/views/pages/register.php" class="me-4 nav-link text-decoration-none  text-white h5">Register</a>
                <a href="/views/pages/login.php" class="me-4 nav-link text-decoration-none  text-white h5">Login</a>
        <?php endif; ?>
        <?php if(isset($_SESSION['user_info']) && !$_SESSION['user_info']['isAdmin']): ?>
                <a href="/views/pages/lists.php" class="me-4 nav-link text-decoration-none  text-white h5">List</a>
                <a href="/views/pages/weather.php" class="me-4 nav-link text-decoration-none  text-white h5">Weather</a>
                <a href="/views/pages/locations.php" class="me-4 nav-link text-decoration-none  text-white h5">Locations</a>
                <a href="/views/pages/stories.php" class="me-4 nav-link text-decoration-none  text-white h5">Stories</a>
                <a href="/controllers/users/logout_process.php" class="me-4 nav-link text-decoration-none  text-white h5">Log Out</a>
        <?php endif; ?>
        <?php if(isset($_SESSION['user_info']) && $_SESSION['user_info']['isAdmin']):?>
                <a href="/views/pages/admins/locations.php" class="me-4 text-white h5 text-decoration-none nav-link">Locations</a>
                <a href="/views/pages/admins/weather.php" class="me-4 text-white h5 text-decoration-none nav-link">Weather</a>
                <a href="/views/pages/admins/stories.php" class="me-4 text-white h5 text-decoration-none nav-link">Stories</a>
                <a href="/controllers/users/logout_process.php" class="me-4 nav-link text-decoration-none  text-white h5">Log Out</a>
        <?php endif; ?>

  </div>
        </nav>