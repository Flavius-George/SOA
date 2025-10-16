<?php
session_start();

$host = 'db';
$user = 'user';
$pass = 'pass';
$dbname = 'fs177';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Conexiune esuata: " . $e->getMessage());
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $error = 'Completeaza toate campurile.';
    } else {
        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificarea parolei (ideal hashing)
        if ($user && $password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: index.php');
                exit;
            } elseif ($user['role'] === 'user') {
                header('Location: index_user.php');
                exit;
            } else {
                $error = 'Rol necunoscut.';
            }
        } else {
            $error = 'Username sau parola incorecta.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="login-register.css" />
</head>
<body>
    <div class="container">
        <h2>Autentificare</h2>
        <?php if ($error): ?>
            <div class="error" style="color: red; margin-bottom: 15px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="login.php">
            <input type="text" name="username" placeholder="Nume utilizator" required />
            <input type="password" name="password" placeholder="Parolă" required />
            <button type="submit">Login</button>
        </form>
        <p>Nu ai cont? <a href="register.php">Inregistreaza-te aici</a></p>
    </div>
</body>
</html>

