
<?php

include "includes/header.php";
require_once "classes/user.php";

$user = new User;

session_start();
$user->sessionCheck();


if (isset($_POST['submit'])) {
    $user->userSignup($_POST['username'] , $_POST['useremail'] , $_POST['userpass'] , $_FILES['profile_pic'] );
}

?>

<br>
<br>
<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group mb-0">
          <div class="card p-4">
            <div class="card-body">
              <h1>Register</h1>
              <p class="text-muted">Create your account</p>
              <form action="" method="post" enctype="multipart/form-data">

                  <div class="input-group mb-3">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Username">
                  </div>
                  <div class="input-group mb-3">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="useremail" class="form-control" placeholder="Email">
                  </div>
                  <div class="input-group mb-4">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="userpass" class="form-control" placeholder="Password">
                  </div>
                  <div class="input-group mb-3">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="file" name="profile_pic" id="" accept="image/*">
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <button type="submit" name="submit" class="btn btn-primary px-4">Register</button>
                    </div>
                    <div class="col-6 text-right">
                      <button type="button" class="btn btn-link px-0">Forgot password?</button>
                    </div>
                  </div>
              </form>
            </div>
          </div>
          <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <h2>Already have an account</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <a href="index.php">
                <button type="button" class="btn btn-primary active mt-3">Login Now!</button>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php

include "includes/footer.php";

?>