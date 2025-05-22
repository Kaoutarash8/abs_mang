document.addEventListener('DOMContentLoaded', function() {
    // Gestion des tableaux avec tri
    const tables = document.querySelectorAll('.data-table');
    
    tables.forEach(table => {
        const headers = table.querySelectorAll('th[data-sort]');
        
        headers.forEach(header => {
            header.addEventListener('click', () => {
                const sortKey = header.getAttribute('data-sort');
                const isAscending = header.classList.contains('asc');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                
                // Toggle direction
                headers.forEach(h => h.classList.remove('asc', 'desc'));
                header.classList.toggle('asc', !isAscending);
                header.classList.toggle('desc', isAscending);
                
                // Trier les lignes
                rows.sort((a, b) => {
                    const aValue = a.querySelector(`td[data-${sortKey}]`).textContent;
                    const bValue = b.querySelector(`td[data-${sortKey}]`).textContent;
                    
                    if (sortKey === 'date') {
                        return isAscending 
                            ? new Date(aValue) - new Date(bValue)
                            : new Date(bValue) - new Date(aValue);
                    } else {
                        return isAscending
                            ? aValue.localeCompare(bValue)
                            : bValue.localeCompare(aValue);
                    }
                });
                
                // Réinsérer les lignes triées
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    });
    
    // Confirmation avant suppression
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                e.preventDefault();
            }
        });
    });
    
    // Gestion des onglets
    const tabs = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.getAttribute('data-tab');
            
            // Gérer l'état actif des onglets
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            // Afficher le contenu correspondant
            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === target) {
                    content.classList.add('active');
                }
            });
        });
    });
    
    // Initialisation des selects avec select2 (si présent)
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    }
});