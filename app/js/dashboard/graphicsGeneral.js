/* Tiempo de procesos */

graphicTimeProcessByProduct = (data) => {};

/* Costo carga fabril */

graphicsFactoryLoad = (data) => {

    machine = [];
    costMinute = [];
    totalCostMinute = 0;

    for (let i in data) {
        machine.push(data[i].machine);
        costMinute.push(data[i].totalCostMinute);
        totalCostMinute = totalCostMinute + costMinute[i];
    }

    $('#factoryLoadCost').html(totalCostMinute.toLocaleString('es-ES'));

    const cmc = document.getElementById('charFactoryLoadCost');
    const charFactoryLoadCost = new Chart(cmc, {
        type: 'doughnut',
        data: {
            labels: machine,
            formatter: function(value, context) {
                return context.chart.data.labels[context.dataIndex];
            },
            datasets: [{
                data: costMinute,
                backgroundColor: getRandomColor(data.length),
                //borderColor: [],
                borderWidth: 1,
            }, ],
        },
        options: {
            /* scales: {
                y: {
                    beginAtZero: true,
                },
            }, */
        },
        plugins: [ChartDataLabels],
        options: {
            plugins: {
                legend: {
                    display: false
                },
            },
        },

    });

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
        plugins: [ChartDataLabels],
        /* options: {
            legend: {
                display: false,
            },
        } */

    });
};

/* Indidcadores globales */

graphicProfit = (data) => {
    const cmo = document.getElementById('charExpensesGenerals');
    const charExpensesGenerals = new Chart(cmo, {
        type: 'doughnut',
        data: {
            labels: ['Utilidad'],
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