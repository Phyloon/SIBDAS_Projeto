/**
 * Main initialization when DOM is fully loaded
 */
document.addEventListener('DOMContentLoaded', function () {

    // ================================================================
    // 1. FETCH EQUIPMENT DETAILS FOR MODIFY MODAL
    //    When a user selects an equipment from dropdown, fetch its data
    //    via AJAX and populate the modification form fields.
    // ================================================================
    const equipmentSelect = document.getElementById('equipmentSelect');

    if (equipmentSelect) {
        equipmentSelect.addEventListener('change', function() {
            const equipmentId = this.value;
            if (!equipmentId) return;

            fetch(`../includes/get_equipment_details.php?id=${equipmentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const eq = data.equipment;
                        
                        // Populate hidden ID field
                        if (document.getElementById('mod_id')) {
                            document.getElementById('mod_id').value = eq.id;
                        }
                        
                        // Populate text/input fields
                        if (document.getElementById('mod_nome')) document.getElementById('mod_nome').value = eq.nome || '';
                        if (document.getElementById('mod_modelo')) document.getElementById('mod_modelo').value = eq.modelo || '';
                        if (document.getElementById('mod_marca')) document.getElementById('mod_marca').value = eq.marca || '';
                        if (document.getElementById('mod_serial')) document.getElementById('mod_serial').value = eq.serial || '';
                        if (document.getElementById('mod_ano_fabrico')) document.getElementById('mod_ano_fabrico').value = eq.ano_fabrico || '';
                        if (document.getElementById('mod_location_floor')) document.getElementById('mod_location_floor').value = eq.location_floor || '';
                        if (document.getElementById('mod_location_room')) document.getElementById('mod_location_room').value = eq.location_room || '';
                        if (document.getElementById('mod_custo_aquisicao')) document.getElementById('mod_custo_aquisicao').value = eq.custo_aquisicao || '';
                        if (document.getElementById('mod_data_aquisicao')) document.getElementById('mod_data_aquisicao').value = eq.data_aquisicao || '';
                        if (document.getElementById('mod_descricao')) document.getElementById('mod_descricao').value = eq.descricao || '';
                        
                        // Populate dropdown selects (with fallback)
                        if (document.getElementById('mod_estado')) {
                            document.getElementById('mod_estado').value = eq.estado || 'Disponivel';
                        } else {
                            const estadoSel = document.querySelector('[name="estado"]');
                            if (estadoSel) estadoSel.value = eq.estado || 'Disponivel';
                        }

                        if (document.getElementById('mod_criticidade')) {
                            document.getElementById('mod_criticidade').value = eq.criticidade || '';
                        } else {
                            const critSel = document.querySelector('[name="criticidade"]');
                            if (critSel) critSel.value = eq.criticidade || '';
                        }

                        if (document.getElementById('mod_location_wing')) document.getElementById('mod_location_wing').value = eq.location_wing || '';
                        if (document.getElementById('mod_departamento')) document.getElementById('mod_departamento').value = eq.departamento || '';
                        if (document.getElementById('mod_grupo')) document.getElementById('mod_grupo').value = eq.grupo || '';
                        if (document.getElementById('mod_tipo_aquisicao')) document.getElementById('mod_tipo_aquisicao').value = eq.tipo_aquisicao || '';
                        
                        // Multi‑supplier mappings (if present)
                        if (document.getElementById('mod_fornecedor_id')) {
                            document.getElementById('mod_fornecedor_id').value = eq.fornecedor_id || eq.fornecedor_fabricante || '';
                        }
                        if (document.getElementById('mod_fornecedor_distribuidor')) {
                            document.getElementById('mod_fornecedor_distribuidor').value = eq.fornecedor_distribuidor || '';
                        }
                        if (document.getElementById('mod_fornecedor_manutencao')) {
                            document.getElementById('mod_fornecedor_manutencao').value = eq.fornecedor_manutencao || '';
                        }
                        if (document.getElementById('mod_fornecedor_consumiveis')) {
                            document.getElementById('mod_fornecedor_consumiveis').value = eq.fornecedor_consumiveis || '';
                        }
                    } else {
                        console.error('Error fetching details:', data.message);
                    }
                })
                .catch(error => console.error('Network Error:', error));
        });
    }

    // ================================================================
    // 2. FILTER BAR: Live filtering of tables and cards
    //    Listeners for search input and dropdowns on both .filter-bar
    //    and .filter-bar-modal (for different pages).
    // ================================================================
    if (document.querySelector('.filter-bar')) {
        document.querySelector('.filter-bar input').addEventListener('input', applyFilters);
        document.querySelectorAll('.filter-bar select').forEach(select => {
            select.addEventListener('change', applyFilters);
        });
    }

    if (document.querySelector('.filter-bar-modal')) {
        document.querySelector('.filter-bar-modal input')?.addEventListener('input', applyFilters);
        document.querySelectorAll('.filter-bar-modal select').forEach(select => {
            select.addEventListener('change', applyFilters);
        });
    }

    // ================================================================
    // 3. SEARCHBAR FOR FORNECEDORES PAGE
    //    Filters suppliers by name and/or type, and shows/hides
    //    equipment cards accordingly.
    // ================================================================
    const searchInput = document.getElementById('supplierSearchInput');
    const typeFilter  = document.getElementById('supplierTypeFilter');
    if (searchInput) {
        searchInput.addEventListener('input', e => {
            let filter = e.target.value.toLowerCase().trim();
            
            document.querySelectorAll('#suppliers-accordion > .accordion-item').forEach(acc => {
                let isSupplierMatch = acc.querySelector('.accordion-header').textContent.toLowerCase().includes(filter);
                let hasGearMatch = false;

                acc.querySelectorAll('.col-3').forEach(card => {
                    let showCard = isSupplierMatch || card.textContent.toLowerCase().includes(filter);
                    card.style.display = showCard ? '' : 'none';
                    if (showCard) hasGearMatch = true;
                });

                acc.style.display = (filter === '' || isSupplierMatch || hasGearMatch) ? '' : 'none';
            });
        });
    }
    if (typeFilter) typeFilter.addEventListener('change', applyFilters);

    // ================================================================
    // 4. STATUS BAR GRAPH (Dashboard)
    //    Fetches equipment status counts from backend and renders a
    //    horizontal bar chart with a special 'Total' bar.
    // ================================================================
    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
        fetch('/private/includes/4_bar_graph.php')
            .then(response => response.json())
            .then(statusData => {
                if (statusData.error) {
                    console.error(statusData.error);
                    return;
                }
                
                const totalEquipments = 
                    (statusData.Used || 0) + 
                    (statusData.Broken || 0) + 
                    (statusData.Maintenance || 0) + 
                    (statusData.Available || 0);
                
                const ctx = statusCanvas.getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Used', 'Broken', 'Maint.', 'Available', 'Total'],
                        datasets: [{
                            data: [
                                statusData.Used || 0,
                                statusData.Broken || 0,
                                statusData.Maintenance || 0,
                                statusData.Available || 0,
                                totalEquipments
                            ],
                            backgroundColor: ['#10b981', '#ef4444', '#f59e0b', '#3b82f6', '#64748b'],
                            borderRadius: 8
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
                    }   
                });
            })
            .catch(err => console.error('Error loading chart:', err));
    }

    // ================================================================
    // 5. ADDITIONAL DASHBOARD CHARTS (Location, Documents, Suppliers)
    //    Uses a global variable 'dashboardData' injected by PHP.
    // ================================================================
    if (typeof dashboardData !== 'undefined') {
        const colors = ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6610f2'];

        // Equipment by wing (doughnut)
        const locCanvas = document.getElementById('locationChart');
        if (locCanvas) {
            new Chart(locCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: dashboardData.locLabels,
                    datasets: [{ data: dashboardData.locCounts, backgroundColor: colors, borderWidth: 0 }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right', labels: { boxWidth: 12 } } } }
            });
        }

        // Document types (pie)
        const docsCanvas = document.getElementById('docsChart');
        if (docsCanvas) {
            new Chart(docsCanvas.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: dashboardData.docLabels,
                    datasets: [{ data: dashboardData.docCounts, backgroundColor: colors.slice().reverse(), borderWidth: 0 }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right', labels: { boxWidth: 12 } } } }
            });
        }

        // Top suppliers (horizontal bar)
        const suppCanvas = document.getElementById('suppliersChart');
        if (suppCanvas) {
            new Chart(suppCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: dashboardData.fornLabels,
                    datasets: [{ label: 'Equipamentos', data: dashboardData.fornCounts, backgroundColor: '#0d6efd', borderRadius: 4 }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }
    }

    // ================================================================
    // 6. CLEAR FILTERS BUTTON
    //    Resets all filter dropdowns and search input to default, then
    //    reapplies the (now empty) filters.
    // ================================================================
    const clearBtn = document.getElementById('clearFiltersBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            document.querySelectorAll('.filter-bar-modal select').forEach(select => {
                select.selectedIndex = 0;
            });
            const searchInput = document.querySelector('.filter-bar-modal input');
            if (searchInput) searchInput.value = '';
            
            applyFilters();
        });
    }
});

/**
 * Global filter function used by search and dropdown changes.
 * Applies filters on table rows, equipment cards, and supplier accordion.
 */
function applyFilters() {
    // Read all filter values from DOM elements
    const wing         = document.querySelector('select[data-filter="wing"]')?.value;
    const floor        = document.querySelector('select[data-filter="floor"]')?.value;
    const department   = document.querySelector('select[data-filter="department"]')?.value;
    const search       = document.querySelector('.filter-bar input')?.value.toLowerCase() || document.querySelector('.filter-bar-modal input')?.value.toLowerCase() || '';
    const role         = document.querySelector('select[data-filter="role"]')?.value;
    const availability = document.querySelector('select[data-filter="availability"]')?.value;
    const group        = document.querySelector('select[data-filter="group"]')?.value;
    const criticality  = document.querySelector('select[data-filter="criticality"]')?.value;

    // -----------------------------------------------------------------
    // Filter TABLE rows (staff, location, etc.)
    // -----------------------------------------------------------------
    document.querySelectorAll('tbody tr').forEach(row => {
        const matchWing         = !wing || wing === 'All Wings' || row.dataset.wing === wing;
        const matchFloor        = !floor || floor === 'All Floors' || row.dataset.floor === floor;
        const matchDept         = !department || department === 'All Departments' || row.dataset.department === department;
        const matchSearch       = !search || search === '' || row.textContent.toLowerCase().includes(search);
        const matchRole         = !role || role === 'All Roles' || row.dataset.role === role;
        const matchAvailability = !availability || availability === 'All Availability' || row.dataset.availability === availability;

        row.style.display = (matchWing && matchFloor && matchDept && matchSearch && matchRole && matchAvailability) ? '' : 'none';
    });

    // -----------------------------------------------------------------
    // Filter CARDS (inventory grid)
    // -----------------------------------------------------------------
    document.querySelectorAll('[data-group]').forEach(card => {
        const matchGroup       = !group || group === 'Grupo' || card.dataset.group === group;
        const matchAvail       = !availability || availability === 'Disponibilidade' || card.dataset.availability === availability;
        const matchDept        = !department || department === 'Departamento' || card.dataset.department === department;
        const matchSearch      = !search || card.textContent.toLowerCase().includes(search);
        const matchCriticality = !criticality || criticality === "Criticidade" || card.dataset.criticality === criticality;

        card.style.display = (matchGroup && matchAvail && matchDept && matchSearch && matchCriticality) ? '' : 'none';
    });

    // -----------------------------------------------------------------
    // Filter SUPPLIERS ACCORDION (fornecedores page)
    // -----------------------------------------------------------------
    const supplierSearch = document.getElementById('supplierSearchInput')?.value.toLowerCase().trim() || '';
    const supplierType   = document.getElementById('supplierTypeFilter')?.value || '';

    document.querySelectorAll('#suppliers-accordion > .accordion-item').forEach(acc => {
        const matchType = !supplierType || acc.dataset.type === supplierType;
        const headerText = acc.querySelector('.accordion-header').textContent.toLowerCase();
        const matchHeader = headerText.includes(supplierSearch);

        let hasVisibleCards = false;
        acc.querySelectorAll('.col-3').forEach(card => {
            const cardText = card.textContent.toLowerCase();
            const showCard = matchType && (supplierSearch === '' || cardText.includes(supplierSearch));
            card.style.display = showCard ? '' : 'none';
            if (showCard) hasVisibleCards = true;
        });

        // Show accordion if header matches OR any card matches, and type matches
        const shouldShow = matchType && (supplierSearch === '' || matchHeader || hasVisibleCards);
        acc.style.display = shouldShow ? '' : 'none';
    });
}