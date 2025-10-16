<?php
session_start();
include 'db.php';

if (!$conn) {
    die("Conexiunea la baza de date a eșuat: " . mysqli_connect_error());
}

// Ștergere
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM persoane WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

// Actualizare
if (isset($_POST['update_id'])) {
    $id = intval($_POST['update_id']);
    $nume = $_POST['nume'];
    $prenume = $_POST['prenume'];
    $cetatenie = $_POST['cetatenie'];
    $tip_document = $_POST['tip_document'];
    $nr_document = $_POST['nr_document'];
    $data_trecere = $_POST['data_trecere'];
    $sens = $_POST['sens'];

    $stmt = $conn->prepare("UPDATE persoane SET nume=?, prenume=?, cetatenie=?, tip_document=?, nr_document=?, data_trecere=?, sens=? WHERE id=?");
    $stmt->bind_param("sssssssi", $nume, $prenume, $cetatenie, $tip_document, $nr_document, $data_trecere, $sens, $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

$sql = "SELECT * FROM persoane ORDER BY data_trecere DESC";
$result = $conn->query($sql);
$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

$cetatenii = [];
$tipuri = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $c = strtolower(trim($row['cetatenie']));
        $t = strtolower(trim($row['tip_document']));
        if ($c !== '' && !in_array($c, $cetatenii)) $cetatenii[] = $c;
        if ($t !== '' && !in_array($t, $tipuri)) $tipuri[] = $t;
    }
    sort($cetatenii);
    sort($tipuri);
    $result->data_seek(0);
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Admin Persoane</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .btn { padding: 6px 12px; border-radius: 4px; margin-right: 6px; cursor: pointer; border: none; }
        .btn-update { background: #28a745; color: white; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-edit { background: #007bff; color: white; }
        .filter-btn.active { background: #007bff; }
        .filter-btn { padding: 6px 10px; margin: 4px 2px; border: none; cursor: pointer; background: #ccc; border-radius: 3px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        #search { width: 100%; padding: 8px; margin-top: 10px; }
    </style>
</head>
<body>

<h1>Administrare Persoane</h1>
<a href="add.php" class="btn btn-edit">Adaugă</a>
<a href="statistica.php" class="btn" style="background: orange;">Statistici</a>
<a href="logout.php" class="btn" style="background: gray;">Logout</a>

<input type="text" id="search" placeholder="Caută în tabel...">

<div>
    <strong>Sens:</strong>
    <button class="filter-btn filter-sens active" data-filter="all">Toate</button>
    <button class="filter-btn filter-sens" data-filter="intrare">Intrare</button>
    <button class="filter-btn filter-sens" data-filter="iesire">Ieșire</button>
</div>

<div>
    <strong>Cetățenie:</strong>
    <button class="filter-btn filter-cetatenie active" data-filter="all">Toate</button>
    <?php foreach ($cetatenii as $c): ?>
        <button class="filter-btn filter-cetatenie" data-filter="<?= htmlspecialchars($c) ?>"><?= ucfirst(htmlspecialchars($c)) ?></button>
    <?php endforeach; ?>
</div>

<div>
    <strong>Tip Document:</strong>
    <button class="filter-btn filter-tipdoc active" data-filter="all">Toate</button>
    <?php foreach ($tipuri as $t): ?>
        <button class="filter-btn filter-tipdoc" data-filter="<?= htmlspecialchars($t) ?>"><?= ucfirst(htmlspecialchars($t)) ?></button>
    <?php endforeach; ?>
</div>

<div>
    <strong>Data:</strong>
    <button class="filter-btn filter-data active" data-filter="all">Toate</button>
    <button class="filter-btn filter-data" data-filter="today">Astăzi</button>
</div>

<table id="tabel">
    <thead>
        <tr>
            <th>Nume</th><th>Prenume</th><th>Cetățenie</th><th>Tip Doc</th><th>Nr Doc</th><th>Data</th><th>Sens</th><th>Acțiuni</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php if ($edit_id === intval($row['id'])): ?>
                <tr>
                    <td colspan="8">
                        <form method="post" action="">
                            <input type="hidden" name="update_id" value="<?= intval($row['id']) ?>">
                            <input name="nume" value="<?= htmlspecialchars($row['nume']) ?>" required>
                            <input name="prenume" value="<?= htmlspecialchars($row['prenume']) ?>" required>
                            <input name="cetatenie" value="<?= htmlspecialchars($row['cetatenie']) ?>">
                            <input name="tip_document" value="<?= htmlspecialchars($row['tip_document']) ?>">
                            <input name="nr_document" value="<?= htmlspecialchars($row['nr_document']) ?>">
                            <input type="date" name="data_trecere" value="<?= htmlspecialchars($row['data_trecere']) ?>">
                            <select name="sens">
                                <option value="Intrare" <?= $row['sens'] === 'Intrare' ? 'selected' : '' ?>>Intrare</option>
                                <option value="Iesire" <?= $row['sens'] === 'Iesire' ? 'selected' : '' ?>>Ieșire</option>
                            </select>
                            <button class="btn btn-update" type="submit">Salvează</button>
                            <a href="index.php" class="btn">Renunță</a>
                        </form>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td><?= htmlspecialchars($row['nume']) ?></td>
                    <td><?= htmlspecialchars($row['prenume']) ?></td>
                    <td><?= htmlspecialchars($row['cetatenie']) ?></td>
                    <td><?= htmlspecialchars($row['tip_document']) ?></td>
                    <td><?= htmlspecialchars($row['nr_document']) ?></td>
                    <td><?= htmlspecialchars($row['data_trecere']) ?></td>
                    <td><?= htmlspecialchars($row['sens']) ?></td>
                    <td>
                        <a href="index.php?edit=<?= intval($row['id']) ?>" class="btn btn-edit">Edit</a>
                        <form method="post" action="" style="display:inline;" onsubmit="return confirm('Ștergi?');">
                            <input type="hidden" name="delete_id" value="<?= intval($row['id']) ?>">
                            <button class="btn btn-delete" type="submit">Șterge</button>
                        </form>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endwhile; ?>
    </tbody>
</table>

<script>
let filterSens = 'all';
let filterCetatenie = 'all';
let filterTipDoc = 'all';
let filterData = 'all';

function aplicaFiltre() {
    const rows = document.querySelectorAll('#tabel tbody tr');
    const search = document.getElementById('search').value.toLowerCase();
    const azi = new Date().toISOString().slice(0, 10);

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length < 7) return; // Skip form rows

        const cetatenie = cells[2].textContent.toLowerCase();
        const tipdoc = cells[3].textContent.toLowerCase();
        const sens = cells[6].textContent.toLowerCase();
        const data = cells[5].textContent;

        const matchSens = filterSens === 'all' || sens === filterSens;
        const matchCet = filterCetatenie === 'all' || cetatenie === filterCetatenie;
        const matchTip = filterTipDoc === 'all' || tipdoc === filterTipDoc;
        const matchData = filterData === 'all' || (filterData === 'today' && data === azi);
        const matchSearch = row.textContent.toLowerCase().includes(search);

        row.style.display = (matchSens && matchCet && matchTip && matchData && matchSearch) ? '' : 'none';
    });
}

function setupButtons(className, callback) {
    document.querySelectorAll(className).forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll(className).forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            callback(btn.getAttribute('data-filter'));
            aplicaFiltre();
        });
    });
}

setupButtons('.filter-sens', val => filterSens = val);
setupButtons('.filter-cetatenie', val => filterCetatenie = val);
setupButtons('.filter-tipdoc', val => filterTipDoc = val);
setupButtons('.filter-data', val => filterData = val);

document.getElementById('search').addEventListener('input', aplicaFiltre);
</script>

</body>
</html>
