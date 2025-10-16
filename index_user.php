<?php
session_start();

include 'db.php';

if (!$conn) {
    die("Conexiunea la baza de date a eșuat: " . mysqli_connect_error());
}

// Preluăm toate datele
$sql = "SELECT * FROM persoane ORDER BY data_trecere DESC";
$result = $conn->query($sql);

// Construim listele unice pentru filtre
$cetatenii = [];
$tipuri = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $c = strtolower(trim($row['cetatenie']));
        $t = strtolower(trim($row['tip_document']));

        if ($c !== '' && !in_array($c, $cetatenii)) {
            $cetatenii[] = $c;
        }
        if ($t !== '' && !in_array($t, $tipuri)) {
            $tipuri[] = $t;
        }
    }
    sort($cetatenii);
    sort($tipuri);

    // Resetăm pointerul rezultatelor pentru afișarea tabelului
    $result->data_seek(0);
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Vizualizare Persoane</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h1 { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; background: white; }
        #search { width: 100%; padding: 8px; margin-top: 20px; font-size: 16px; box-sizing: border-box; }
        .filters-group { margin-top: 15px; }
        .filters-label { font-weight: bold; margin-right: 10px; }
        .filter-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 6px 12px;
            margin-right: 5px;
            border-radius: 3px;
            cursor: pointer;
        }
        .filter-btn.active { background-color: #007bff; }
    </style>
</head>
<body>

<h1>Vizualizare Persoane</h1>

<input type="text" id="search" placeholder="Caută în tabel...">

<!-- Filtre -->
<div class="filters-group">
    <span class="filters-label">Sens:</span>
    <button class="filter-btn filter-sens active" data-filter="all">Toate</button>
    <button class="filter-btn filter-sens" data-filter="intrare">Intrare</button>
    <button class="filter-btn filter-sens" data-filter="iesire">Ieșire</button>
</div>

<div class="filters-group">
    <span class="filters-label">Cetățenie:</span>
    <button class="filter-btn filter-cetatenie active" data-filter="all">Toate</button>
    <?php foreach ($cetatenii as $c): ?>
        <button class="filter-btn filter-cetatenie" data-filter="<?= htmlspecialchars($c) ?>"><?= ucfirst(htmlspecialchars($c)) ?></button>
    <?php endforeach; ?>
</div>

<div class="filters-group">
    <span class="filters-label">Tip Document:</span>
    <button class="filter-btn filter-tipdoc active" data-filter="all">Toate</button>
    <?php foreach ($tipuri as $t): ?>
        <button class="filter-btn filter-tipdoc" data-filter="<?= htmlspecialchars($t) ?>"><?= ucfirst(htmlspecialchars($t)) ?></button>
    <?php endforeach; ?>
</div>

<div class="filters-group">
    <span class="filters-label">Data Trecere:</span>
    <button class="filter-btn filter-data active" data-filter="all">Toate</button>
    <button class="filter-btn filter-data" data-filter="today">Astăzi</button>
</div>

<table id="tabel">
    <thead>
        <tr>
            <th>Nume</th>
            <th>Prenume</th>
            <th>Cetățenie</th>
            <th>Tip Document</th>
            <th>Nr. Document</th>
            <th>Data Trecere</th>
            <th>Sens</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nume']) ?></td>
                    <td><?= htmlspecialchars($row['prenume']) ?></td>
                    <td><?= htmlspecialchars($row['cetatenie']) ?></td>
                    <td><?= htmlspecialchars($row['tip_document']) ?></td>
                    <td><?= htmlspecialchars($row['nr_document']) ?></td>
                    <td><?= htmlspecialchars($row['data_trecere']) ?></td>
                    <td><?= htmlspecialchars($row['sens']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">Nu există date.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    let filterSens = 'all';
    let filterCetatenie = 'all';
    let filterTipDoc = 'all';
    let filterData = 'all';

    function aplicaFiltre() {
        const searchVal = document.getElementById('search').value.toLowerCase();
        const rows = document.querySelectorAll('#tabel tbody tr');

        rows.forEach(row => {
            const sens = row.cells[6]?.textContent.toLowerCase() || '';
            const cetatenie = row.cells[2]?.textContent.toLowerCase() || '';
            const tipdoc = row.cells[3]?.textContent.toLowerCase() || '';
            const datatrecere = row.cells[5]?.textContent || '';
            const text = row.textContent.toLowerCase();

            const sensOK = (filterSens === 'all' || sens === filterSens);
            const cetOK = (filterCetatenie === 'all' || cetatenie === filterCetatenie);
            const tipOK = (filterTipDoc === 'all' || tipdoc === filterTipDoc);

            let dataOK = true;
            if (filterData === 'today') {
                const azi = new Date().toISOString().slice(0, 10);
                dataOK = (datatrecere === azi);
            }

            const searchOK = text.includes(searchVal);
            row.style.display = (sensOK && cetOK && tipOK && dataOK && searchOK) ? '' : 'none';
        });
    }

    function setupFilterButtons(selector, callback) {
        document.querySelectorAll(selector).forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll(selector).forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                callback(btn.getAttribute('data-filter').toLowerCase());
                aplicaFiltre();
            });
        });
    }

    setupFilterButtons('.filter-sens', val => filterSens = val);
    setupFilterButtons('.filter-cetatenie', val => filterCetatenie = val);
    setupFilterButtons('.filter-tipdoc', val => filterTipDoc = val);
    setupFilterButtons('.filter-data', val => filterData = val);

    document.getElementById('search').addEventListener('keyup', aplicaFiltre);

    aplicaFiltre();
</script>

</body>
</html>
