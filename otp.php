<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>OTP Verification</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
<div class="otp-bg">
  <div class="overlay"></div>
  <div class="otp-container">
    <h2 class="text mb-3 text-center">Verify Your Account</h2>
    <p class="text text-center">Enter the 6-digit code sent to your phone number.</p>

    <form id="verifyForm" action="verify_otp.php" method="POST">
      <div class="otp-inputs">
        <input type="text" maxlength="1" name="otp[]" />
        <input type="text" maxlength="1" name="otp[]" />
        <input type="text" maxlength="1" name="otp[]" />
        <input type="text" maxlength="1" name="otp[]" />
        <input type="text" maxlength="1" name="otp[]" />
        <input type="text" maxlength="1" name="otp[]" />
      </div>
      <button type="submit" class="btn w-100 py-2">Verify</button>
    </form>

    <div class="resend">
      Didn't receive the code? <a href="#" id="resendBtn">Resend Code</a>
    </div>

    <div id="otpMessage" class="otp-message"></div>
  </div>
</div>

<script>
(function () {
  const form = document.getElementById('verifyForm');
  const inputs = document.querySelectorAll('.otp-inputs input');
  const resendBtn = document.getElementById('resendBtn');
  const message = document.getElementById('otpMessage');

  function showMessage(text, isError = false) {
    message.textContent = text;
    message.style.color = isError ? '#ff4d4d' : '#00e0ff';
  }

  inputs.forEach((input, idx) => {
    input.addEventListener('input', (e) => {
      if (e.target.value.length > 1) e.target.value = e.target.value.slice(-1);
      if (e.target.value && idx < inputs.length - 1) inputs[idx + 1].focus();
    });
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !e.target.value && idx > 0) {
        inputs[idx - 1].focus();
      }
    });
  });

  form.addEventListener('submit', (e) => {
    const otp = Array.from(inputs).map(i => i.value).join('');
    if (otp.length !== 6) {
      e.preventDefault();
      showMessage('Please enter all 6 digits.', true);
    } else {
      showMessage('Verifying OTP...');
    }
  });

  resendBtn.addEventListener('click', (e) => {
    e.preventDefault();
    showMessage('Resending code...');
  });
})();
</script>
</body>
</html>
