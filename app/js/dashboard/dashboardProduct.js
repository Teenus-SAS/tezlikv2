/* DashboardProducts */
$(document).ready(function() {
    id_product = sessionStorage.getItem('idProduct');

    fetch(`/api/dashboardPricesProducts/${id_product}`)
        .then(response => response.text())
        .then(data => {
            data = JSON.parse(data)
            generalIndicators(data.cost_product)
            UnitsVolSold(data.cost_product)
                //totalCost(data)
            graphicCostExpenses(data.cost_product)
            graphicCostWorkforce(data.cost_workforce)
                /* graphicCostMaterials()
                graphicCostTimeProcess() */

        });

    /* Colors */

    dynamicColors = () => {
        let letters = '0123456789ABCDEF'.split('');
        let color = '#';

        for (var i = 0; i < 6; i++)
            color += letters[Math.floor(Math.random() * 16)];

        return color;
    }

    getRandomColor = (a) => {
        let color = [];
        for (i = 0; i < a; i++)
            color.push(dynamicColors());

        debugger
        return color;
    }

    /* Indicadores Generales */

    generalIndicators = (data) => {
        $('#rawMaterial').html(data[0].cost_materials.toLocaleString('es-ES'));
        $('#workforce').html(data[0].cost_workforce.toLocaleString('es-ES'));
        $('#indirectCost').html(data[0].cost_indirect_cost.toLocaleString('es-ES'));
        $('#assignableExpenses').html(data[0].assignable_expense.toLocaleString('es-ES'));
    }

    /* Ventas */

    UnitsVolSold = (data) => {
        $('#unitsSold').html(data[0].units_sold.toLocaleString('es-ES'));
        $('#turnover').html(data[0].turnover.toLocaleString('es-ES'));

    }

    /* Costeo Total */

    totalCost = () => {
        cost = parseFloat(r[0].cost_materials) + parseFloat(r[0].cost_workforce) + parseFloat(r[0].cost_indirect_cost);
        costTotal = cost + parseFloat(r[0].assignable_expense);

        $('#costTotal').html(costTotal.toLocaleString('es-ES'));
        $('#cost').html(cost.toLocaleString('es-ES'));
        $('#payRawMaterial').html(r[0].cost_materials.toLocaleString('es-ES'));
        $('#payWorkforce').html(r[0].cost_workforce.toLocaleString('es-ES'));
        $('#payIndirectCost').html(r[0].cost_indirect_cost.toLocaleString('es-ES'));
        $('#payAssignableExpenses').html(r[0].assignable_expense.toLocaleString('es-ES'));
        $('#commisionSale').html(`${r[0].commision_sale}%`);
        $('#profitability').html(`${r[0].profitability}%`);
    }


    /* Costo del producto */

    graphicCostExpenses = (data) => {
        const ctx = document.getElementById('chartProductCosts').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Mano de Obra', 'Materia Prima', 'Costos Indirectos', 'Gastos Generales'],
                datasets: [{
                    label: '',
                    data: [data[0].cost_workforce, data[0].cost_materials, data[0].cost_indirect_cost, data[0].assignable_expense],
                    backgroundColor: getRandomColor(4),
                    //borderColor: [getRandomColor()],
                    borderWidth: 1,
                }, ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    }


    /* Mano de Obra */

    graphicCostWorkforce = (data) => {

        process = []
        workforce = []

        for (let i in data) {
            process.push(data[i].process);
            workforce.push(data[i].workforce);
        }

        const cmo = document.getElementById('charWorkForce').getContext('2d');
        const charWorkForce = new Chart(cmo, {
            type: 'doughnut',
            data: {
                labels: process,
                datasets: [{
                    //label: '# of Tomatoes',
                    data: workforce,
                    backgroundColor: getRandomColor(data.length),
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
    }

    /* Tiempo de Proceso del producto */


    graphicCostTimeProcess = (data) => {

        var cmo = document.getElementById('charTimeProcess');
        var charWorkForce = new Chart(cmo, {
            type: 'doughnut',
            data: {
                labels: [
                    // dataTimeProcess.process,

                ],
                datasets: [{
                    //label: '# of Tomatoes',
                    data: [

                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
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
    }

    /* Costos de la materia prima */
    graphicCostMaterials = (data) => {
        const cmc = document.getElementById('chartMaterialsCosts').getContext('2d');

        const chartMaterials = new Chart(cmc, {
            type: 'bar',
            data: {
                labels: [

                ],
                datasets: [{
                    data: [

                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                    ],
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
    }
});