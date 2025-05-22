document.addEventListener('DOMContentLoaded', function() {
    // Initialize absence chart
    const initAbsenceChart = () => {
        const ctx = document.getElementById('absenceChart').getContext('2d');
        
        window.absenceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                datasets: [
                    {
                        label: 'Absences',
                        data: [5, 8, 3, 6, 4, 1],
                        borderColor: '#6a3093',
                        backgroundColor: 'rgba(106, 48, 147, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Retards',
                        data: [2, 3, 1, 4, 2, 0],
                        borderColor: '#00c9b7',
                        backgroundColor: 'rgba(0, 201, 183, 0.1)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre'
                        }
                    }
                }
            }
        });
    };

    // Initialize the page
    initAbsenceChart();
});