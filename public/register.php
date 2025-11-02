<?php

// $addressList = ["add1", "add1", "add1","add1","add1","add1","add1","add1","add1"];


?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unity Register</title>
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="assets/logo.png" alt="Unity logo">
    </div>

        <div class="form-section">
            <div class="card">
                <h2>Register</h2>
                <form id="registerForm" action="../request/register.php?register=1" method="POST">
                  <div class="input-group">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="FirstName" name="FirstName" placeholder="First Name">
                    </div>

            <div class="input-group">
              <i class="fa-solid fa-user"></i>
              <input type="text" id="LastName" name="LastName" placeholder="Last Name">
            </div>

            <div class="input-group">
                <i class="fa-solid fa-phone"></i>
                <input type="number" id="phNum" name="number" placeholder="Mobile Number">
            </div>

            <div class="input-group">
              <i class="fa-solid fa-envelope"></i>
              <input type="email" id="email" name="email" placeholder="Email">
            </div>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select><br><br>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required><br><br>

            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="2" cols="30"></textarea>

            <!-- <label for="gender">A</label>
            <select name="address">
                <option value="">Select Address</option>
                <?php foreach ($addressList as $add): ?>
                  <option value="<?= $add ?>"><?= $add ?></option>
                <?php endforeach; ?>  
            </select><br><br> -->

            <div class="input-group">
              <i class="fa-solid fa-user"></i>
              <input type="text" id="uName" name="username" placeholder="Username">
            </div>

            <div class="input-group">
              <i class="fa-solid fa-lock"></i>
              <input type="password" id="password" name="password" placeholder="Password">
            </div>

            <div class="input-group">
              <i class="fa-solid fa-lock"></i>
              <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
            </div>
          </div>

          <button type="submit" class="btn">Register</button>
          <div id="message"></div>

          <div class="signup">
            Already have an account? <a href="index.html">Login here</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
  <script src="../js/main.js"></script>
</body>
</html>
