<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection if not already done
// Example: require_once("config.php");

if (isset($_POST['signup'])) {
    $fname = trim($_POST['fullname']);
    $email = trim($_POST['emailid']);
    $mobile = trim($_POST['mobileno']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];

    // Server-side validation
    if ($password !== $confirmPassword) {
        echo "<script>alert('Password and Confirm Password do not match!');</script>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO tblusers (FullName, EmailId, ContactNo, Password) 
                VALUES (:fname, :email, :mobile, :password)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        
        if ($query->execute()) {
            echo "<script>alert('Registration successful. Now you can login');</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again');</script>";
        }
    }
}
?>

<!-- JavaScript password validation -->
<script type="text/javascript">
function valid() {
    const pw = document.signup.password.value.trim();
    const cpw = document.signup.confirmpassword.value.trim();

    if (pw !== cpw) {
        alert("Password and Confirm Password do not match!");
        document.signup.confirmpassword.focus();
        return false;
    }
    return true;
}
</script>

<!-- AJAX email availability check -->
<script>
function checkAvailability() {
    $("#loaderIcon").show();
    jQuery.ajax({
        url: "check_availability.php",
        data: 'emailid=' + $("#emailid").val(),
        type: "POST",
        success: function(data) {
            $("#user-availability-status").html(data);
            $("#loaderIcon").hide();
        },
        error: function () {
            $("#loaderIcon").hide();
        }
    });
}
</script>

<!-- Signup Form -->
<div class="modal fade" id="signupform">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h3 class="modal-title">Sign Up</h3>
      </div>
      <div class="modal-body">
        <form method="post" name="signup" onsubmit="return valid();">
          <div class="form-group">
            <input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
          </div>
          <div class="form-group">
            <input type="text" name="mobileno" class="form-control" placeholder="Mobile Number" maxlength="10" required>
          </div>
          <div class="form-group">
            <input type="email" name="emailid" id="emailid" class="form-control" placeholder="Email Address" onblur="checkAvailability()" required>
            <span id="user-availability-status" style="font-size:12px;"></span>
          </div>
          <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="new-password" required>
          </div>
          <div class="form-group">
            <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password" autocomplete="new-password" required>
          </div>
          <div class="form-group checkbox">
            <input type="checkbox" id="terms_agree" required checked>
            <label for="terms_agree">I Agree with <a href="#">Terms and Conditions</a></label>
          </div>
          <div class="form-group">
            <input type="submit" name="signup" value="Sign Up" class="btn btn-block btn-primary">
          </div>
        </form>
      </div>
      <div class="modal-footer text-center">
        <p>Already got an account? <a href="#loginform" data-toggle="modal" data-dismiss="modal">Login Here</a></p>
      </div>
    </div>
  </div>
</div>
