<?php
session_start();

$host = '10.13.11.6';
$user = 'fs177';
$pass = 'wa6ohrgq';
$dbname = 'fs177';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Conexiune esuata: " . $e->getMessage());
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $role = $_POST['role'] ?? 'user';

    if (!in_array($role, ['user', 'admin'])) {
        $error = 'Rol invalid selectat.';
    } elseif ($username === '' || $password === '' || $password_confirm === '') {
        $error = 'Completeaza toate campurile.';
    } elseif ($password !== $password_confirm) {
        $error = 'Parolele nu coincid.';
    } else {
        // Verifica daca username-ul exista deja
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            $error = 'Numele de utilizator exista deja.';
        } else {
            // Insereaza parola fara hash (atenție: nu recomand pentru proiecte reale!)
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt->execute([
                'username' => $username,
                'password' => $password,
                'role' => $role
            ]);

            $success = 'Inregistrare reusita! Acum te poti autentifica.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inregistrare Utilizator</title>
    <link rel="stylesheet" href="login-register.css" />
</head>
<body>
  <div class="container">
    <h2>Înregistrare</h2>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" action="register.php" autocomplete="off">
      <input type="text" name="username" placeholder="Nume utilizator" required />
      <input type="password" name="password" placeholder="Parolă" required />
      <input type="password" name="password_confirm" placeholder="Confirmă parola" required />
      <select name="role" required>
        <option value="user" selected>User</option>
        <option value="admin">Admin</option>
      </select>
      <button type="submit">Înregistrează-te</button>
    </form>

    <p class="message">Ai deja cont? <a href="login.php">Autentifică-te</a></p>
  </div>
</body>
</html>
