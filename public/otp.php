<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verification Code</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/verification.css" />
</head>
<body>

  <div class="verify-container">
    <h3>Verify Your Account</h3>
    <p>Enter the 6-digit code sent to your phone number.</p>

    <form id="verifyForm" >
      <div class="otp-input">
        <input type="text" maxlength="1" required>
        <input type="text" maxlength="1" required>
        <input type="text" maxlength="1" required>
        <input type="text" maxlength="1" required>
        <input type="text" maxlength="1" required>
        <input type="text" maxlength="1" required>
      </div>
      <button type="submit">Verify</button>
    </form>

    <div class="resend">
      Didn't receive the code? <a href="#" id="resendBtn">Resend Code</a>
    </div>
  </div>
  <script src="view/js/main.js"></script>
    <!-- ajax response to request/otp.php -->
  <script>
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
        showMessage('Please enter a 6-digit code.', true);
        return;
      }

      showMessage('Verifying...');

      try {
        const body = new URLSearchParams();
        body.append('otp', otp);

        const res = await fetch('../request/otpVerification.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: body.toString()
        });

        // Expect JSON response from the server
        const data = await res.json();

        if (res.ok && data.success) {
          showMessage(data.message || 'OTP verified successfully.');
          // optional: redirect after short delay
          setTimeout(() => {
            window.location.href = 'login.php';
          }, 900);
        } else {
          showMessage(data.message || 'OTP verification failed.', true);
        }

      } catch (err) {
        showMessage('Network error. Please try again.', true);
        console.error(err);
      }
    });

    // placeholder for resend action
    resendBtn.addEventListener('click', (e) => {
      e.preventDefault();
      showMessage('Resend requested — functionality not implemented yet.');
      // TODO: call server endpoint to resend OTP
    });
  })();

  </script>

</body>
</html>