<?php
require_once 'config/database.php';
require_once 'models/EmployeeModel.php';

$database = new Database();
$db = $database->getConnection();
$employeeModel = new EmployeeModel($db);

$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

switch ($action) {
  // DASHBOARD
  case 'dashboard':
    $dashboard = $employeeModel->getDashboardSummary();
    include 'views/dashboard.php';
    break;

  // DAFTAR KARYAWAN
  case 'list':
    $employees = $employeeModel->getAllEmployees();
    include 'views/employee_list.php';
    break;

  // TAMBAH DATA BARU
  case 'create':
    if ($_POST) {
      $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'department' => $_POST['department'],
        'position' => $_POST['position'],
        'salary' => $_POST['salary'],
        'hire_date' => $_POST['hire_date']
      ];
      if ($employeeModel->createEmployee($data)) {
        header("Location: index.php?action=list&message=created");
      } else {
        $error = "Gagal menambah karyawan";
      }
    }
    include 'views/employee_form.php';
    break;

  // EDIT DATA
  case 'edit':
    $id = $_GET['id'];
    if ($_POST) {
      $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'department' => $_POST['department'],
        'position' => $_POST['position'],
        'salary' => $_POST['salary'],
        'hire_date' => $_POST['hire_date']
      ];
      if ($employeeModel->updateEmployee($id, $data)) {
        header("Location: index.php?action=list&message=updated");
      } else {
        $error = "Gagal mengupdate karyawan";
      }
    }
    $employee = $employeeModel->getEmployeeById($id);
    include 'views/employee_form.php';
    break;

  // HAPUS DATA
  case 'delete':
    $id = $_GET['id'];
    if ($employeeModel->deleteEmployee($id)) {
      header("Location: index.php?action=list&message=deleted");
    } else {
      header("Location: index.php?action=list&message=delete_error");
    }
    break;

  // STATISTIK DEPARTEMEN
  case 'department_stats':
    $stats = $employeeModel->getDepartmentStats();
    include 'views/department_stats.php';
    break;

  // STATISTIK GAJI
  case 'salary_stats':
    include 'views/salary_stats.php';
    break;

  // STATISTIK MASA KERJA
  case 'tenure_stats':
    include 'views/tenure_stats.php';
    break;

  // RINGKASAN KARYAWAN
  case 'employee_overview':
    include 'views/employee_overview.php';
    break;

  // REFRESH DASHBOARD MATERIALIZED VIEW
  case 'refresh':
    $employeeModel->refreshDashboard();
    header("Location: index.php?action=dashboard&message=refreshed");
    break;

  // DEFAULT
  default:
    $dashboard = $employeeModel->getDashboardSummary();
    include 'views/dashboard.php';
    break;
}
?>
