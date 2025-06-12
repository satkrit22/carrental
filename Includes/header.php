<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<header>
  <div class="default-header">
    <div class="container">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <div style="scale: 3;" class="logo">
            <a href="index.php"><img src="assets/images/favicon-icon/favicon.png" alt="image"/></a>
          </div>
        </div>

        <div class="col-sm-9 col-md-10">
          <div class="header_info">
            <?php
            $sql = "SELECT EmailId, ContactNo FROM tblcontactusinfo LIMIT 1";
            $query = $dbh->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);

            $email = $result ? $result->EmailId : 'support@example.com';
            $contactno = $result ? $result->ContactNo : 'N/A';
            ?>

            <div class="header_widgets">
              <div class="circle_icon"><i class="fa fa-envelope" aria-hidden="true"></i></div>
              <p class="uppercase_text">FOR SUPPORT MAIL US:</p>
              <a href="mailto:<?php echo htmlentities($email); ?>"><?php echo htmlentities($email); ?></a>
            </div>

            <div class="header_widgets">
              <div class="circle_icon"><i class="fa fa-phone" aria-hidden="true"></i></div>
              <p class="uppercase_text">SERVICE HELPLINE CALL US:</p>
              <a href="tel:<?php echo htmlentities($contactno); ?>"><?php echo htmlentities($contactno); ?></a>
            </div>

            <div class="social-follow"></div>

            <?php if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) { ?>
              <div class="login_btn">
                <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal">Login / Register</a>
              </div>
            <?php } else { ?>
              <div class="welcome-msg">
                Welcome to Car Rental Portal
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <nav id="navigation_bar" class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button id="menu_slide" data-target="#navigation" aria-expanded="false" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>

      <div class="header_wrap">
        <div class="user_login">
          <ul>
            <li class="dropdown">
              <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-user-circle" aria-hidden="true"></i>
                <?php
                if (isset($_SESSION['login'])) {
                  $email = $_SESSION['login'];
                  $sql = "SELECT FullName FROM tblusers WHERE EmailId = :email LIMIT 1";
                  $query = $dbh->prepare($sql);
                  $query->bindParam(':email', $email, PDO::PARAM_STR);
                  $query->execute();
                  $user = $query->fetch(PDO::FETCH_OBJ);
                  if ($user) {
                    echo htmlentities($user->FullName);
                  }
                }
                ?>
                <i class="fa fa-angle-down" aria-hidden="true"></i>
              </a>
              <ul class="dropdown-menu">
                <?php if (isset($_SESSION['login'])) { ?>
                  <li><a href="profile.php">Profile Settings</a></li>
                  <li><a href="update-password.php">Update Password</a></li>
                  <li><a href="my-booking.php">My Booking</a></li>
                  <li><a href="post-testimonial.php">Post a Testimonial</a></li>
                  <li><a href="my-testimonials.php">My Testimonial</a></li>
                  <li><a href="logout.php">Sign Out</a></li>
                <?php } ?>
              </ul>
            </li>
          </ul>
        </div>

        <div class="header_search">
          <div id="search_toggle"><i class="fa fa-search" aria-hidden="true"></i></div>
          <form action="search.php" method="post" id="header-search-form">
            <input type="text" placeholder="Search..." name="searchdata" class="form-control" required>
            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
          </form>
        </div>
      </div>

      <div class="collapse navbar-collapse" id="navigation">
        <ul class="nav navbar-nav">
          <li><a href="index.php">Home</a></li>
          <li><a href="page.php?type=aboutus">About Us</a></li>
          <li><a href="car-listing.php">Car Listing</a></li>
          <!-- <li><a href="page.php?type=faqs">FAQs</a></li> -->
          <li><a href="contact-us.php">Contact Us</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Navigation end -->
</header>
