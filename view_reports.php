<?php include 'sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Reports</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <main class="main-content reports-page">
    <header>
      <h1>My Reports</h1>
      <p>Track the progress and approval status of your submitted reports</p>
    </header>

    <section class="report-grid">
      <!-- Report 1 -->
      <div class="report-card">
        <div class="report-image">
          <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e" alt="Report Image" />
          <span class="status-label pending">Pending</span>
        </div>
        <div class="report-info">
          <h3>Broken Street Light</h3>
          <p class="category">Category: <span>Infrastructure</span></p>
          <p class="desc">Dim street light near Barangay Hall.</p>
          <p class="location">📍 Lat: 13.98644, Lng: 121.12667</p>
          <p class="date">📅 Reported on 2025-10-19 20:45</p>

          <!-- Progress bar -->
          <div class="progress-tracker">
            <div class="step active">
              <div class="circle">🕓</div>
              <p>Pending</p>
            </div>
            <div class="line"></div>
            <div class="step">
              <div class="circle">⚙️</div>
              <p>In Progress</p>
            </div>
            <div class="line"></div>
            <div class="step">
              <div class="circle">✅</div>
              <p>Resolved</p>
            </div>
          </div>

          <button class="btn-view">View Details</button>
        </div>
      </div>

      <!-- Report 2 -->
      <div class="report-card">
        <div class="report-image">
          <img src="https://images.unsplash.com/photo-1482192596544-9eb780fc7f66" alt="Report Image" />
          <span class="status-label progress">In Progress</span>
        </div>
        <div class="report-info">
          <h3>Flooded Road</h3>
          <p class="category">Category: <span>Safety</span></p>
          <p class="desc">Road near school area flooded after heavy rain.</p>
          <p class="location">📍 Lat: 13.98320, Lng: 121.11890</p>
          <p class="date">📅 Reported on 2025-10-20 14:22</p>

          <div class="progress-tracker">
            <div class="step">
              <div class="circle">🕓</div>
              <p>Pending</p>
            </div>
            <div class="line active"></div>
            <div class="step active">
              <div class="circle">⚙️</div>
              <p>In Progress</p>
            </div>
            <div class="line"></div>
            <div class="step">
              <div class="circle">✅</div>
              <p>Resolved</p>
            </div>
          </div>

          <button class="btn-view">View Details</button>
        </div>
      </div>

      <!-- Report 3 -->
      <div class="report-card">
        <div class="report-image">
          <img src="https://images.unsplash.com/photo-1565182999561-18d7dc59c42f" alt="Report Image" />
          <span class="status-label resolved">Resolved</span>
        </div>
        <div class="report-info">
          <h3>Garbage Overflow</h3>
          <p class="category">Category: <span>Environment</span></p>
          <p class="desc">Uncollected garbage in public area resolved.</p>
          <p class="location">📍 Lat: 13.98420, Lng: 121.12450</p>
          <p class="date">📅 Reported on 2025-10-18 09:15</p>

          <div class="progress-tracker">
            <div class="step">
              <div class="circle">🕓</div>
              <p>Pending</p>
            </div>
            <div class="line active"></div>
            <div class="step">
              <div class="circle">⚙️</div>
              <p>In Progress</p>
            </div>
            <div class="line active"></div>
            <div class="step active">
              <div class="circle">✅</div>
              <p>Resolved</p>
            </div>
          </div>

          <button class="btn-view">View Details</button>
        </div>
      </div>
    </section>
  </main>

  <script>
    // Light hover animation
    document.querySelectorAll(".report-card").forEach((card) => {
      card.addEventListener("mouseover", () => {
        card.style.transform = "translateY(-4px)";
        card.style.boxShadow = "0 6px 16px rgba(0,0,0,0.15)";
      });
      card.addEventListener("mouseout", () => {
        card.style.transform = "translateY(0)";
        card.style.boxShadow = "0 4px 12px rgba(0,0,0,0.1)";
      });
    });
  </script>
</body>
</html>
