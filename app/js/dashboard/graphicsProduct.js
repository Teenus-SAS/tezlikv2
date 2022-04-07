/* DashboardProducts */
$(document).ready(function () {
  /* Costo del producto */

  graphicCostExpenses = (data) => {
    const ctx = document.getElementById('chartProductCosts').getContext('2d');
    const myChart = new Chart(ctx, {
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
            data: [
              data[0].cost_workforce,
              data[0].cost_materials,
              data[0].cost_indirect_cost,
              data[0].assignable_expense,
            ],
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

    const cmo = document.getElementById('charWorkForce').getContext('2d');
    const charWorkForce = new Chart(cmo, {
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

    var cmo = document.getElementById('charTimeProcess');
    var charWorkForce = new Chart(cmo, {
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
        },
      },
    });
  };
});
