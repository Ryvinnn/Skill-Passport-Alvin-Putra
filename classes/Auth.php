
<?php

class Auth {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($username, $password) {

        $sql = "SELECT * FROM users WHERE Username = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['Password'])) {

                session_regenerate_id(true);

                $_SESSION['UserID'] = $user['UserID'];
                $_SESSION['Nama'] = $user['Nama'];
                $_SESSION['Username'] = $user['Username'];
                $_SESSION['Role'] = $user['Role'];

                return true;
            }
        }

        return false;
    }

    public function checkLogin() {
        return isset($_SESSION['UserID']);
    }

    public function checkRole($role) {

        return isset($_SESSION['Role']) &&
               $_SESSION['Role'] === $role;
    }

    public function logout() {

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}
?>
