<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Services/ResponseService.php';
require_once __DIR__ . '/../Models/User.php';
class SuperAdminController
{
    public static function getMember()
    {
        $members = User::all();
        require __DIR__ . '/../../pages/superAdmin/member.php';
    }
    public static function create(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseService::json(false, 'Invalid request method', [], 405);
        }

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }
        $name     = htmlspecialchars(trim($_POST['name'] ?? ''));
        $emailRaw = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $type   = htmlspecialchars(trim($_POST['type'] ?? ''));
        $status   = htmlspecialchars(trim($_POST['status'] ?? ''));
        $role   = htmlspecialchars(trim($_POST['role'] ?? ''));
        if ($name === '') {
            ResponseService::json(false, 'Name is required', [], 422);
        }
        if (!filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
            ResponseService::json(false, 'Invalid email format', [], 422);
        }
        $email = $emailRaw;

        if ($password === '') {
            ResponseService::json(false, 'Password is required', [], 422);
        }
        if ($type === '') {
            ResponseService::json(false, 'type is required', [], 422);
        }
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $request=[
            'name'=>$name,
            'email'=>$email,
            'password'=>$passwordHash,
            'status'=>$status,
            'role'=>$role,
            'type'=>$type
        ];
        $res=User::create($request);
        if($res){
            ResponseService::json(true, 'Create user successful', []);
        }
        
    }
}
