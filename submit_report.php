<?php include 'sidebar.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main-content">
      <header>
        <h1>Submit Report</h1>
        <p>Fill out the details below to submit your report.</p>
      </header>

      <!-- Form Section -->
      <section class="form-section">
        <form>
          <div class="form-group">
            <label for="title">Report Title</label>
            <input type="text" id="title" name="title" placeholder="Enter report title" required>
          </div>

          <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
              <option value="">-- Select Category --</option>
              <option value="Infrastructure">Infrastructure</option>
              <option value="Environment">Environment</option>
              <option value="Health">Health</option>
              <option value="Safety">Safety</option>
            </select>
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" placeholder="Describe your report..." required></textarea>
          </div>

          <div class="form-group">
            <label for="image">Upload Image (optional)</label>
            <input type="file" id="image" name="image" accept="image/*">
          </div>

          <div class="form-group">
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" readonly required>
    </div>

          <button type="submit" class="btn-submit">Submit Report</button>
        </form>
      </section>
    </main>

</body>
</html>