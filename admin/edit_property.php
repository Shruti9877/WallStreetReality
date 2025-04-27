<?php
$host = 'localhost';
$db   = 'real_estate';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Database connection failed: " . $e->getMessage());
}

// Function to show error and stop execution
function showError($message) {
    echo "<h2>$message</h2>";
    echo "<a href='dashboard.php'>← Back to Properties</a>";
    exit;
}

// Validate and get the property ID from URL
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    showError("Invalid or missing property ID.");
}

try {
    $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->execute([(int)$id]);
    $property = $stmt->fetch();

    if (!$property) {
        showError("Property not found!");
    }

} catch (PDOException $e) {
    showError("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Property</title>
  <style>
    /* Global styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #2c3e50;
    }

    form {
      background: #ffffff;
      padding: 30px 40px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
      animation: fadeIn 0.6s ease-out;
    }

    label {
      margin-top: 15px;
      font-weight: bold;
      display: block;
      color: #34495e;
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      font-size: 15px;
      border: 2px solid #ccc;
      border-radius: 8px;
      transition: border 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus,
    select:focus,
    textarea:focus {
      border-color: #3498db;
      box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
      outline: none;
    }

    textarea {
      resize: vertical;
    }

    img {
      margin-top: 10px;
      border-radius: 8px;
      max-width: 100%;
      height: auto;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      animation: fadeIn 0.5s ease-in;
    }

    button {
      margin-top: 25px;
      padding: 12px 20px;
      background-color: #3498db;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 16px;
      width: 100%;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button:hover {
      background-color: #2980b9;
      transform: scale(1.02);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 600px) {
      body {
        padding: 20px;
      }

      form {
        padding: 25px;
      }
    }
  </style>
</head>
<body>

  <form action="update_property.php" method="POST" enctype="multipart/form-data">
    <h2>Edit Property</h2>
    <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">

    <label>Title:</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($property['title']); ?>" required>

    <label>Type:</label>
    <select name="type" required>
      <option value="Apartment" <?php if ($property['type'] == 'Apartment') echo 'selected'; ?>>Apartment</option>
      <option value="Villa" <?php if ($property['type'] == 'Villa') echo 'selected'; ?>>Villa</option>
      <option value="Plot" <?php if ($property['type'] == 'Plot') echo 'selected'; ?>>Plot</option>
      <option value="mansion" <?php if ($property['type'] == 'mansion') echo 'selected'; ?>>mansion</option>
    </select>

    <label>Price:</label>
    <input type="number" name="price" value="<?php echo $property['price']; ?>" required>

    <label>Bedrooms:</label>
    <input type="number" name="bedrooms" value="<?php echo $property['bedrooms']; ?>" required>

    <label>Bathrooms:</label>
    <input type="number" name="bathrooms" value="<?php echo $property['bathrooms']; ?>" required>

    <label>Area (sqft):</label>
    <input type="number" name="area" value="<?php echo $property['area']; ?>" required>

    <label>Description:</label>
    <textarea name="description" rows="4" required><?php echo htmlspecialchars($property['description']); ?></textarea>

    <label>Current Image:</label>
    <?php if (!empty($property['image'])): ?>
      <img src="uploads/<?php echo htmlspecialchars($property['image']); ?>" alt="Property Image">
    <?php else: ?>
      <p>No image uploaded.</p>
    <?php endif; ?>

    <label>Change Image:</label>
    <input type="file" name="image">

    <button type="submit" name="update">Update Property</button>
  </form>

</body>
</html>
