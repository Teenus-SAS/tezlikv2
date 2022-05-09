/* DashboardProducts */
$(document).ready(function () {
  /* Costo del producto */

  graphicCostExpenses = (data) => {
    costExpenses = [];

    costExpenses.push(data[0].cost_workforce);
    costExpenses.push(data[0].cost_materials);
    costExpenses.push(data[0].cost_indirect_cost);
    costExpenses.push(data[0].assignable_expense);

    const ctx = document.getElementById('chartProductCosts').getContext('2d');
    const myChart = new Chart(ctx, {
      plugins: [ChartDataLabels],
      type: 'bar',
      data: {
        labels: [
          'Mano de Obra',
          'Materia Prima',
          'Costos Indirectos',
          'Gastos Generales',
        ],
        datasets: [
          {
            data: costExpenses,
            backgroundColor: getRandomColor(4),
            //borderColor: [getRandomColor()],
            borderWidth: 1,
          },
        ],
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
          datalabels: {
            anchor: 'end',
            formatter: (costExpenses) => costExpenses.toLocaleString(),
            color: 'black',
            font: {
              size: '14',
              weight: 'bold',
            },
          },
        },
      },
    });
  };

  /* Mano de Obra */

  graphicCostWorkforce = (data) => {
    process = [];
    workforce = [];

    for (let i in data) {
      process.push(data[i].process);
      workforce.push(data[i].workforce);
    }

    const cmo = document.getElementById('chartWorkForce').getContext('2d');
    const chartWorkForce = new Chart(cmo, {
      plugins: [ChartDataLabels],
      type: 'doughnut',
      data: {
        labels: process,
        datasets: [
          {
            data: workforce,
            backgroundColor: getRandomColor(data.length),
            //borderColor: [],
            borderWidth: 1,
          },
        ],
      },
      options: {
        plugins: {
          legend: {
            display: false,
          },
          datalabels: {
            formatter: (value, ctx) => {
              let sum = 0;
              let dataArr = ctx.chart.data.datasets[0].data;
              dataArr.map((data) => {
                sum += data;
              });
              let percentage = ((value * 100) / sum).toFixed(2) + '%';
              return ctx.chart.data.labels[ctx.dataIndex] + '\n' + percentage;
            },
            color: 'black',
            font: {
              size: '14',
              weight: 'bold',
            },
          },
        },
      },
    });
  };

  /* Tiempo de Proceso del producto */

  graphicCostTimeProcess = (data) => {
    process = [];
    totalTime = [];

    for (let i in data) {
      process.push(data[i].process);
      totalTime.push(data[i].totalTime);
    }

    var cmo = document.getElementById('chartTimeProcess');
    var chartWorkForce = new Chart(cmo, {
      plugins: [ChartDataLabels],
      type: 'doughnut',
      data: {
        labels: process,
        datasets: [
          {
            data: totalTime,
            backgroundColor: getRandomColor(data.length),
            //borderColor: [],
            borderWidth: 1,
          },
        ],
      },
      options: {
        plugins: {
          legend: {
            display: false,
          },
          datalabels: {
            formatter: (value, ctx) => {
              let sum = 0;
              let dataArr = ctx.chart.data.datasets[0].data;
              dataArr.map((data) => {
                sum += data;
              });
              let percentage = ((value * 100) / sum).toFixed(2) + '%';
              return ctx.chart.data.labels[ctx.dataIndex] + '\n' + percentage;
            },
            color: 'black',
            font: {
              size: '14',
              weight: 'bold',
            },
          },
        },
      },
    });
  };

  /* Costos de la materia prima */
  graphicCostMaterials = (data) => {
    material = [];
    totalMaterial = [];

    for (let i in data) {
      material.push(data[i].material);
      totalMaterial.push(data[i].totalCostMaterial);
    }

    const cmc = document.getElementById('chartMaterialsCosts').getContext('2d');
    const chartMaterials = new Chart(cmc, {
      plugins: [ChartDataLabels],
      type: 'bar',
      data: {
        labels: material,
        datasets: [
          {
            data: totalMaterial,
            backgroundColor: getRandomColor(data.length),
            //borderColor: [],
            borderWidth: 1,
          },
        ],
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
          datalabels: {
            anchor: 'end',
            formatter: (totalMaterial) => totalMaterial.toLocaleString(),
            color: 'black',
            font: {
              size: '14',
              weight: 'bold',
            },
          },
        },
      },
    });
  };
});
