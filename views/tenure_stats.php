<?php

include 'views/header.php';
require_once __DIR__ . '/../config/database.php';

$db = new database();
$conn = $db->getconnection();

$sql = "
  with base as (
    select
      department,
      extract(year from age(current_date, hire_date))::int as years
    from employees
  )
  select
    coalesce(department, 'total') as department,
    sum(case when years < 1 then 1 else 0 end)  as junior,
    sum(case when years between 1 and 3 then 1 else 0 end) as middle,
    sum(case when years > 3 then 1 else 0 end) as senior
  from base
  group by rollup(department)
  order by case when department is null then 1 else 0 end, department
";
$stmt = $conn->prepare($sql);
$stmt->execute();
?>
<h2>statistik masa kerja (junior/middle/senior)</h2>
<table class="data-table">
  <thead>
    <tr>
      <th>departemen</th>
      <th>junior (&lt;1 th)</th>
      <th>middle (1–3 th)</th>
      <th>senior (&gt;3 th)</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($r = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
      <tr>
        <td><?= htmlspecialchars($r['department']) ?></td>
        <td><?= (int)$r['junior'] ?></td>
        <td><?= (int)$r['middle'] ?></td>
        <td><?= (int)$r['senior'] ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include 'views/footer.php'; ?>
