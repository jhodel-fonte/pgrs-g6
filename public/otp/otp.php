<?php
session_start();
var_dump($_SESSION['secretOtp']) ."<br>" ."Remove This One after testing";

$_SESSION['otp_sent_time'] = time() + 3;
// require __DIR__ .'../../src/utillities/sessionRouting.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verification Code</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/verification.css" />
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['res'])) {
      if ($_GET['res'] === '1') {
        echo "<script>
          document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: 'Resend Successfully. New OTP Sent',
              confirmButtonText: 'OK'
            });
          });
        </script>";
      } else {
        echo "<script>
          document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Error Resending',
              confirmButtonText: 'OK'
            });
          });
        </script>";
      } 
    }
  
  ?>

  <div class="verify-container">
    <h3>We need to Verify Your Account</h3>
    <?php
    // echo (isset($_SESSION['identity']) && $_SESSION['identity'] == 'register') ? "<h3>We need to Verify Your Account</h3>" : "<h3>We need to Verify Your Account</h3>";
    
    // echo "We Have Sent an OTP to " .$_SESSION['number'];
    ?>

    <p>Enter the 6-digit code sent to <span><?= (isset($_SESSION['number'])) ? htmlspecialchars($_SESSION['number']) : '(Error User Number)'; ?></span></p>

    <form id="verifyForm"> <!-- method="POST" action="../../handlers/otpVerification.php"> -->
      <div class="otp-input">
        <input type="text" maxlength="1" >
        <input type="text" maxlength="1" >
        <input type="text" maxlength="1" >
        <input type="text" maxlength="1" >
        <input type="text" maxlength="1" >
        <input type="text" maxlength="1" >
      </div>
      <!-- <input type="number" name="otp"> -->
      <button type="submit">Verify</button>
    </form>

    <div class="resend"> Didn't receive the code?
        <a id="resendBtn" class="resendButton" hidden>Resend Code</a>
      <p>Time remaining to resend: <span id="countdown"></span></p>
    </div>

    <?php
/*       if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['reg']) && $_GET['reg'] == '1') {
        echo "<p>Verify Your Identity</p>";
      } else {
          echo "<div class='Log-Out'>
                <a href='../../handlers/logout.php?logout=1'>Logout</a>
              </div>";
      } */
    
    ?>
  
    
  </div>

  <script> //time countdown script
    const expirationTimestamp = <?php echo $_SESSION['otp_sent_time'] ?? 0; ?>; //get time expiration
    
    document.addEventListener('DOMContentLoaded', () => {
        let timerInterval = null; 

        const countdownElement = document.getElementById('countdown');
        const expirationTimeMs = expirationTimestamp * 1000; 
        const resendButton = document.getElementById('resendBtn');

        //stop if no set or error
        if (expirationTimeMs <= 0) {
            countdownElement.textContent = "Error Getting Otp Data";
            return;
        }

        resendButton.setAttribute('hidden', 'hidden');

        function updateCountdown() {
            const nowMs = new Date().getTime(); 
            
            //time difference remaining
            const distanceMs = expirationTimeMs - nowMs; 

            if (distanceMs <= 0) {
                clearInterval(timerInterval); 
                countdownElement.innerHTML = '<span style="color: green; font-weight: bold;">You can resend the OTP now.</span>';
                resendButton.removeAttribute('hidden');
                return;
            }

            //milliseconds to seconds
            const seconds = Math.floor(distanceMs / 1000);
            
            // display time
            countdownElement.textContent = `${seconds} seconds`;
        }

        updateCountdown();
        timerInterval = setInterval(updateCountdown, 1000);
    });

    //otp input
    (function () {
      const form = document.getElementById('verifyForm');
      const inputs = document.querySelectorAll('.otp-input input');
      const resendBtn = document.getElementById('resendBtn');

      function showMessage(text, isError = false) {
        let el = document.getElementById('otpMessage');
        if (!el) {
          el = document.createElement('div');
          el.id = 'otpMessage';
          el.style.marginTop = '1rem';
          form.parentNode.insertBefore(el, form.nextSibling);
        }
        el.textContent = text;
        el.style.color = isError ? 'red' : 'green';
      }

      function showSweetAlert(text, isError = false) {
        Swal.fire({
          icon: isError ? 'error' : 'success',
          title: isError ? 'Error' : 'Success',
          text: text,
          confirmButtonText: 'OK'
        });
      }



      // auto move focus and allow backspace
      inputs.forEach((input, idx) => {
        input.addEventListener('input', (e) => {
          const val = e.target.value;
          if (val.length > 1) {
            // keep only last char
            e.target.value = val.slice(-1);
          }
          if (e.target.value && idx < inputs.length - 1) {
            inputs[idx + 1].focus();
          }
        });

        input.addEventListener('keydown', (e) => {
          if (e.key === 'Backspace' && !e.target.value && idx > 0) {
            inputs[idx - 1].focus();
          }
        });
      });

      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const otp = Array.from(inputs).map(i => i.value.trim()).join('');
        if (otp.length !== 6 || !/^\d{6}$/.test(otp)) {
          showSweetAlert('Please enter a 6-digit code.', true);
          return;
        }

        try {
          const body = new URLSearchParams();
          body.append('otp', otp);
          showMessage('Verifying...');

          const res = await fetch('../../handlers/otpVerification.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: body.toString()
          });
          
          // Expect JSON response from the server
          const data = await res.json();
          // console.log('Server Response:', data);

          

          if (res.ok && data.success) {
            showSweetAlert(data.message || 'OTP verified successfully.');
            setTimeout(() => {
              window.location.href = '../../handlers/redirecting.php';
            }, 1500);

          } else {
            showSweetAlert(data.message || 'OTP verification failed.', true);
          }

        } catch (err) {
          showSweetAlert('Network error. Please try again.', true);
          console.error(err);
        }
      });

      // placeholder for resend action
      resendBtn.addEventListener('click', (e) => {
        if (resendBtn.hasAttribute('hidden')) {
          e.preventDefault();
          return;
        }
        e.preventDefault();
        
        // Show green resending message in page instead of SweetAlert
        const countdownElement = document.getElementById('countdown');
        countdownElement.innerHTML = '<span style="color: green; font-weight: bold;">Resending OTP...</span>';
        
        setTimeout(() => {
          window.location.href = 'otp.php?res=1&verification=otp';
        }, 1500);
      });
    })();

  </script>

</body>
</html>