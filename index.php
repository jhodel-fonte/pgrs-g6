<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNITY PGSRS - Padre Garcia Service Report System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./public/assets/landing.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="login.php">UNITY: PGSRS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#contact">Contact</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Portal</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="public/login.php">Login</a></li>
                        <li><a class="dropdown-item" href="./public/register.php">Register</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>



<!-- HOME / HERO + CAROUSEL -->
<section id="home" class="mt-5 pt-4">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

            <div class="carousel-item active">
                <img src="public/assets/img/pgsrsBG.jpg" class="d-block w-100 hero-img" alt="Padre Garcia 1">
                <div class="carousel-caption">
                    <h1 class="fw-bold">Padre Garcia Service Report System</h1>
                    <p>Providing seamless reporting for public service.</p>
                </div>
            </div>

            <div class="carousel-item">
                <img src="public/assets/img/Batangas-PadreGarcia-Most-Holy-Rosary-Parish-Church-1024.jpg" class="d-block w-100 hero-img" alt="Padre Garcia 2">
                <div class="carousel-caption">
                    <h1 class="fw-bold">Fast. Reliable. Transparent.</h1>
                    <p>Your reports matter — together we build a better community.</p>
                </div>
            </div>

            <div class="carousel-item">
                <img src="public/assets/img/pg.jpg" class="d-block w-100 hero-img" alt="Padre Garcia 3">
                <div class="carousel-caption">
                    <h1 class="fw-bold">UNITY PGSRS</h1>
                    <p>The official service reporting system of Padre Garcia.</p>
                </div>
            </div>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- ABOUT SECTION -->
<section id="about" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">About UNITY PGSRS</h2>
        <p class="lead text-center mx-auto" style="max-width: 800px;">
            The UNITY Padre Garcia Service Report System (PGSRS) is a digital platform designed to
            streamline reporting, documentation, and response tracking within the municipality.
            It ensures transparent, fast, and organized communication between departments and citizens.
        </p>
        <P class="lead text-center mx-auto">If nasa login na click lang yung logo ng unity para bumalik sa home page</P>
        <P class="lead text-center mx-auto">check pm na lang po sir para ma access yung admin at user</P>
    </div>
</section>
<section id="gobag">
  <div class="gobag-card">
    <!-- Bag Icon -->
    <div id="bag-icon">
      <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="12" y="16" width="40" height="40" rx="5" stroke="#333" stroke-width="2" fill="#f5f5f5"/>
        <path d="M20 16 C20 8, 44 8, 44 16" stroke="#333" stroke-width="2" fill="none"/>
        <circle cx="24" cy="30" r="3" fill="#00f"/>
        <rect x="32" y="28" width="6" height="6" fill="#ffa500"/>
        <rect x="28" y="38" width="8" height="4" fill="#ff0000"/>
        <rect x="22" y="42" width="4" height="8" fill="#ffff00"/>
      </svg>
    </div>

    <!-- List (hidden by default) -->
    <ul id="bag-list">
      <li><H2>Disaster Preparedness Go Bag</H2></li>
      <li>Water</li>
      <li>Canned foods</li>
      <li>First-aid kit</li>
      <li>Medications</li>
      <li>Flashlight + batteries</li>
      <li>Important documents & cash</li>
      <li>Multi-tool & hygiene items</li>
      <li>Battery-powered or crank radio</li>
    </ul>
  </div>
</section>


<!-- CONTACT SECTION -->
<section id="contact" class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Contact Us</h2>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="4"></textarea>
                    </div>

                    <button class="btn btn-dark w-100">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>


<!-- FOOTER -->
<footer class="bg-dark text-white text-center py-3">
    <p class="mb-0">© 2025 UNITY PGSRS | Padre Garcia Service Report System</p>
</footer>


<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const bagIcon = document.getElementById('bag-icon');
  const bagList = document.getElementById('bag-list');

  bagIcon.addEventListener('click', () => {
    bagList.classList.toggle('show'); 
  });
</script>

</body>
</html>
