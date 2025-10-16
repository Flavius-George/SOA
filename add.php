<?php
include 'db.php'; // conectare la baza de date

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nume = $_POST['nume'];
    $prenume = $_POST['prenume'];
    $cetatenie = $_POST['cetatenie'];
    $tip_document = $_POST['tip_document'];
    $nr_document = $_POST['nr_document'];
    $data_trecere = $_POST['data_trecere'];
    $sens = $_POST['sens'];

    // Pregătim și executăm insert-ul în baza de date
    $stmt = $conn->prepare("INSERT INTO persoane (nume, prenume, cetatenie, tip_document, nr_document, data_trecere, sens) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nume, $prenume, $cetatenie, $tip_document, $nr_document, $data_trecere, $sens);

    if ($stmt->execute()) {
        // După salvare, redirecționăm înapoi la index.php
        header("Location: index.php");
        exit;
    } else {
        echo "Eroare la salvare: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Adauga Persoana</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        /* simplu CSS pentru formular */
        .container {
            width: 400px;
            margin: 50px auto;
            font-family: Arial, sans-serif;
        }
        label {
            display: block;
            margin-top: 15px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        .btn {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .btn-secondary {
            display: inline-block;
            margin-top: 10px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
            width: 100%;
            padding: 10px 0;
            border: 1px solid #007bff;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Adauga Persoana</h1>
        <form method="post" class="form">
            <label>Nume:</label>
            <input type="text" name="nume" required>

            <label>Prenume:</label>
            <input type="text" name="prenume" required>

            <label>Cetatenie:</label>
            <input type="text" name="cetatenie">

            <label>Tip Document:</label>
            <input type="text" name="tip_document">

            <label>Numar Document:</label>
            <input type="text" name="nr_document">

            <label>Data Trecere:</label>
            <input type="date" name="data_trecere">

            <label>Sens:</label>
            <select name="sens">
                <option value="Intrare">Intrare</option>
                <option value="Iesire">Iesire</option>
            </select>

            <button type="submit" class="btn">Salveaza</button>
        </form>
        <a href="index.php" class="btn-secondary">Inapoi</a>
    </div>
</body>
</html>
