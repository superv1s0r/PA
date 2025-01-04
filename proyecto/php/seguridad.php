<?php


class Security {
    public static function isLogged(): bool {
        session_start();
        
        if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            return true;
        }
        if (!empty($_COOKIE['user_email']) && isset($_SESSION['user_email']) && $_COOKIE['user_email'] === $_SESSION['user_email']) {
            return true;
        }
        return false;
    }

    public static function logout(): void {
        session_start();
        
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Delete custom cookies
        setcookie('user_email', '', time() - 3600, '/', '', true, true);

        // Redirect to login page
        self::redirect('login.php');
    }
}
?>
