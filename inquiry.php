<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Property Inquiry</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 700px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    form input, form select, form textarea {
      width: 100%;
      padding: 12px;
      margin: 10px 0 20px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
      font-size: 16px;
    }

    form textarea {
      resize: vertical;
    }

    button {
      background-color: #0052cc;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #003d99;
    }

    .success-message {
      display: none;
      color: green;
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Property Inquiry Form</h2>
    <form action="submit_inquiry.php" method="POST">
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" required>

      <label for="phone">Phone Number</label>
      <input type="tel" id="phone" name="phone" required>
      
      <label for="location">Location</label>
      <input type="text" id="location" name="location" required>


      <label for="property_id">Select Property</label>
      <select id="property_id" name="property_id" required>
        <option value="">-- Choose a Property --</option>
        <?php
// Database connection (replace with your actual DB credentials)
          $dsn = 'mysql:host=localhost;dbname=real_estate;charset=utf8mb4';
          $username = 'root';
          $password = '';

          try {
              $pdo = new PDO($dsn, $username, $password);
              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              // Fetch id, title, and address from properties
              $stmt = $pdo->query("SELECT id, title, location FROM properties ORDER BY title ASC");

              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $id = htmlspecialchars($row['id']);
                  $title = htmlspecialchars($row['title']);
                  $address = htmlspecialchars($row['location']);

                  echo "<option value=\"$id\">$title - $address</option>";
              }
          } catch (PDOException $e) {
              echo '<option disabled>Error fetching properties</option>';
          }
          ?>

        ?>
      </select>

      <label for="message">Your Message</label>
      <textarea id="message" name="message" rows="6" placeholder="I would like to know more about this property..." required></textarea>

      <button type="submit">Submit Inquiry</button>
    </form>
  </div>
</body>
</html>
