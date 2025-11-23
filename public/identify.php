<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capture Photo</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="./assets/camera.css">
</head>

<body>
<div class="camera-bg">
    <div class="camera-box">
        <h2>Take a Photo</h2>
        <video id="camera" autoplay playsinline></video>
        <canvas id="snapshot"></canvas>

        <button id="captureBtn" class="btn btn-capture">📸 Capture Photo</button>
        <button id="retakeBtn" class="btn btn-retake">🔄 Retake Photo</button>
        <button id="useBtn" class="btn btn-use">✔ Use This Photo</button>
    </div>
</div>
<!-- External JS -->
<script src="assets/js/camera.js"></script>

</body>
</html>
