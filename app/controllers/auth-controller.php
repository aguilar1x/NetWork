<?php
require_once 'app/models/user.php';

class AuthController {
    public function login() {
        $user = new User();
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($user->login($username, $password)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error con el usuario']);
        }
    }

}
