
<?php
// Conectare la baza de date
$host = '10.13.11.6';
$user = 'fs177';
$pass = 'wa6ohrgq';
$dbname = 'fs177';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preluăm lista țărilor distincte pentru dropdown
    $stmtCountries = $conn->query("SELECT DISTINCT cetatenie AS tara FROM persoane ORDER BY cetatenie");
    $countries = $stmtCountries->fetchAll(PDO::FETCH_COLUMN);

    // Preluăm țara selectată prin GET
    $selectedCountry = $_GET['tara'] ?? '';

    if ($selectedCountry && in_array($selectedCountry, $countries)) {
        // Dacă e selectată o țară, filtrăm pe ea
        $stmt = $conn->prepare("
            SELECT sens, COUNT(*) as total 
            FROM persoane 
            WHERE cetatenie = :tara
            GROUP BY sens
        ");
        $stmt->execute(['tara' => $selectedCountry]);
    } else {
        // Dacă nu, afișăm totalul pe toate țările
        $selectedCountry = '';
        $stmt = $conn->query("
            SELECT sens, COUNT(*) as total 
            FROM persoane 
            GROUP BY sens
        ");
    }

    $data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $intrari = $data['Intrare'] ?? 0;
    $iesiri = $data['Iesire'] ?? 0;

} catch (PDOException $e) {
    die("Eroare la conectare sau interogare: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Statistici Punct Vamal</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #222;
            color: white;
            text-align: center;
            padding: 30px;
        }
        canvas {
            max-width: 500px;
            margin: 30px auto;
            background: #fff;
            border-radius: 8px;
            display: block;
        }
        button, select {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
        }
        select {
            background-color: white;
            color: black;
            margin-bottom: 20px;
            min-width: 220px;
        }
        p {
            font-size: 18px;
            margin-top: 20px;
        }
        label {
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h1>Statistici Punct Vamal</h1>

    <form method="get" action="statistica.php">
        <label for="tara">Filtrează după cetățenie:</label>
        <select name="tara" id="tara" onchange="this.form.submit()">
            <option value="">Toate țările</option>
            <?php foreach ($countries as $country): ?>
                <option value="<?= htmlspecialchars($country) ?>" <?= $country === $selectedCountry ? 'selected' : '' ?>>
                    <?= htmlspecialchars(ucfirst($country)) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <canvas id="pieChart" width="400" height="400"></canvas>

    <p><strong>Intrări:</strong> <?= $intrari ?> | <strong>Ieșiri:</strong> <?= $iesiri ?></p>

    <button onclick="generatePDF()">Descarcă raport PDF</button>

    <script>
        const ctx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Intrări', 'Ieșiri'],
                datasets: [{
                    data: [<?= $intrari ?>, <?= $iesiri ?>],
                    backgroundColor: ['#4CAF50', '#f44336'],
                    borderColor: ['#fff'],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        labels: {
                            color: '#000'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribuție persoane: Intrare vs Ieșire pentru <?= $selectedCountry ? htmlspecialchars(ucfirst($selectedCountry)) : "toate țările" ?>',
                        color: '#000',
                        font: {
                            size: 18
                        }
                    }
                }
            }
        });

        async function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(18);
            doc.text("Raport Vamal", 20, 20);

            doc.setFontSize(12);
            doc.text("Cetățenie: <?= $selectedCountry ? htmlspecialchars(ucfirst($selectedCountry)) : 'Toate țările' ?>", 20, 30);
            doc.text("Număr Intrări: <?= $intrari ?>", 20, 40);
            doc.text("Număr Ieșiri: <?= $iesiri ?>", 20, 50);

            const canvas = document.getElementById('pieChart');
            const imageData = canvas.toDataURL('image/png');
            doc.addImage(imageData, 'PNG', 20, 60, 160, 120);

            doc.save("raport_vamal.pdf");
        }
    </script>
</body>
</html>
