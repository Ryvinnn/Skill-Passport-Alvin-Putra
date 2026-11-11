
<?php

require_once "../config/koneksi.php";
require_once "../classes/Auth.php";

$auth = new Auth($conn);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header("Location: ../login.php?error=empty");
    exit;
}

if ($auth->login($username, $password)) {

    if ($_SESSION['Role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
        exit;
    }

    if ($_SESSION['Role'] === 'petugas') {
        header("Location: ../petugas/dashboard.php");
        exit;
    }

    $auth->logout();
    header("Location: ../login.php?error=role");
    exit;

} else {

    header("Location: ../login.php?error=invalid");
    exit;

}

?>
