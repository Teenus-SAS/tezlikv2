/* Tiempo de procesos */

graphicTimeProcessByProduct = (data) => {};

/* Costo carga fabril */

graphicsFactoryLoad = (data) => {
    debugger
    machine = [];
    costMinute = [];

    for (let i in data) {
        machine.push(data[i].machine);
        costMinute.push(data[i].totalCostMinute);
    }
    debugger
    const cmc = document.getElementById('charFactoryLoadCost');
    const charFactoryLoadCost = new Chart(cmc, {
        type: 'bar',
        data: {
            labels: machine,
            datasets: [{
                data: costMinute,
                backgroundColor: getRandomColor(data.length),
                //borderColor: [],
                borderWidth: 1,
            }, ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });

    /* Costo mano de obra */
    totalCostMinute = 0;
    for (let i in data) {
        totalCostMinute = totalCostMinute + costMinute[i];
    }
    $('#totalCostWorkforce').html(totalCostMinute.toLocaleString('es-ES'));
};


/* Gastos generales */

graphicGeneralCost = (data) => {

    totalExpense = data.expenseCount51 + data.expenseCount52 + data.expenseCount53;
    $('#totalCost').html(totalExpense.toLocaleString('es-ES'));

    /* Grafico */

    var cmo = document.getElementById('charExpensesGenerals');
    var charExpensesGenerals = new Chart(cmo, {
        type: 'doughnut',
        data: {
            labels: ['Operacionales de administración', 'Gastos de Ventas', 'No operacionales'],
            datasets: [{
                data: [
                    data['expenseCount51'],
                    data['expenseCount52'],
                    data['expenseCount53']
                ],

                backgroundColor: getRandomColor(3),
                //borderColor: [],
                borderWidth: 1,
            }, ],
        },
        options: {
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });
};