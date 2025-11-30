<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

// Check if country parameter is provided and sanitize it
$country = isset($_GET['country']) ? trim($_GET['country']) : '';
$lookup = isset($_GET['lookup']) ? trim($_GET['lookup']) : '';

if ($lookup === 'cities') {
    // Query for cities in the specified country
    if (!empty($country)) {
        $stmt = $conn->prepare("
            SELECT cities.name, cities.district, cities.population
            FROM cities
            JOIN countries ON cities.country_code = countries.code
            WHERE countries.name LIKE :country
            ORDER BY cities.population DESC
        ");
        $stmt->execute(['country' => '%' . $country . '%']);
    } else {
        $stmt = $conn->query("
            SELECT cities.name, cities.district, cities.population
            FROM cities
            JOIN countries ON cities.country_code = countries.code
            ORDER BY cities.population DESC
        ");
    }
} else {
    // Original query for countries
    if (!empty($country)) {
        $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
        $stmt->execute(['country' => '%' . $country . '%']);
    } else {
        $stmt = $conn->query("SELECT * FROM countries");
    }
}

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php if ($lookup === 'cities'): ?>
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>District</th>
      <th>Population</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($results as $row): ?>
    <tr>
      <td><?php echo htmlspecialchars($row['name']); ?></td>
      <td><?php echo htmlspecialchars($row['district']); ?></td>
      <td><?php echo htmlspecialchars($row['population']); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<table>
  <thead>
    <tr>
      <th>Country Name</th>
      <th>Continent</th>
      <th>Independence Year</th>
      <th>Head of State</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($results as $row): ?>
    <tr>
      <td><?php echo htmlspecialchars($row['name']); ?></td>
      <td><?php echo htmlspecialchars($row['continent']); ?></td>
      <td><?php echo $row['independence_year'] ? htmlspecialchars($row['independence_year']) : 'N/A'; ?></td>
      <td><?php echo htmlspecialchars($row['head_of_state']); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
