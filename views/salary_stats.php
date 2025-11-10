<?php
include 'views/header.php';
require_once __DIR__ . '/../config/database.php';

$db = new database();
$conn = $db->getconnection();

$sql = "
  select
    department,
    round(avg(salary), 2) as avg_salary,
    max(salary) as max_salary,
    min(salary) as min_salary
  from employees
  group by department
  order by department
";
$stmt = $conn->prepare($sql);
$stmt->execute();
?>
<h2>statistik gaji per departemen</h2>
<table class="data-table">
  <thead>
    <tr>
      <th>departemen</th>
      <th>rata-rata gaji</th>
      <th>gaji tertinggi</th>
      <th>gaji terendah</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($r = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
      <tr>
        <td><?= htmlspecialchars($r['department']) ?></td>
        <td>rp <?= number_format($r['avg_salary'], 0, ',', '.') ?></td>
        <td>rp <?= number_format($r['max_salary'], 0, ',', '.') ?></td>
        <td>rp <?= number_format($r['min_salary'], 0, ',', '.') ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include 'views/footer.php'; ?>
