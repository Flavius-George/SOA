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
        if (cells.length < 7) return; // evită rândurile de editare

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
