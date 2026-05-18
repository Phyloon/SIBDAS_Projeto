document.querySelector('.filter-bar input').addEventListener('input', applyFilters);
document.querySelectorAll('.filter-bar select').forEach(select => {select.addEventListener('change', applyFilters);});

function applyFilters() {
    const wing       = document.querySelector('select[data-filter="wing"]').value;
    const floor      = document.querySelector('select[data-filter="floor"]').value;
    const department = document.querySelector('select[data-filter="department"]').value;
    const search     = document.querySelector('.filter-bar input').value.toLowerCase();

    document.querySelectorAll('.location-table tbody tr').forEach(row => {
        const matchWing   = wing === 'All Wings'             || row.dataset.wing === wing;
        const matchFloor  = floor === 'All Floors'           || row.dataset.floor === floor;
        const matchDept   = department === 'All Departments' || row.dataset.department === department;
        const matchSearch = search === ''                    || row.textContent.toLowerCase().includes(search);

        row.style.display = (matchWing && matchFloor && matchDept && matchSearch) ? '' : 'none';
    });
}

