<?php
/**
 * file: views/employee_overview.php
 * fungsi: total karyawan, total gaji per bulan, rata-rata masa kerja (count, sum, avg)
 */
include 'views/header.php';
require_once __DIR__ . '/../config/database.php';

$db = new database();
$conn = $db->getconnection();

$summary_sql = "
  select
    count(*) as total_employees,
    sum(salary) as total_monthly_salary,
    round(avg(extract(year from age(current_date, hire_date))), 2) as avg_years_service
  from employees
";
$dept_sql = "
  select department, count(*) as employee_count, round(avg(salary),2) as avg_salary
  from employees
  group by department
  order by department
";

$s = $conn->query($summary_sql)->fetch(PDO::FETCH_ASSOC);
$d = $conn->query($dept_sql);
?>
<h2>ringkasan karyawan</h2>

<div class="dashboard-cards">
  <div class="card">
    <h3>total karyawan</h3>
    <div class="number"><?= (int)$s['total_employees'] ?></div>
  </div>
  <div class="card">
    <h3>total gaji per bulan</h3>
    <div class="number">rp <?= number_format($s['total_monthly_salary'], 0, ',', '.') ?></div>
  </div>
  <div class="card">
    <h3>rata-rata masa kerja</h3>
    <div class="number"><?= $s['avg_years_service'] ?> tahun</div>
  </div>
</div>

<h3>rincian per departemen</h3>
<table class="data-table">
  <thead>
    <tr>
      <th>departemen</th>
      <th>jumlah karyawan</th>
      <th>rata-rata gaji</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($r = $d->fetch(PDO::FETCH_ASSOC)): ?>
      <tr>
        <td><?= htmlspecialchars($r['department']) ?></td>
        <td><?= (int)$r['employee_count'] ?></td>
        <td>rp <?= number_format($r['avg_salary'], 0, ',', '.') ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include 'views/footer.php'; ?>
