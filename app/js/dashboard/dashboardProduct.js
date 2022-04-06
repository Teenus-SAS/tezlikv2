/* DashboardProducts */
$(document).ready(function () {
  // id_product = sessionStorage.getItem('idProduct');

  // fetch(`/api/dashboardPricesProducts/${id_product}`)
  //   .then((response) => response.text())
  //   .then((data) => {
  //     data = JSON.parse(data);
  //     generalIndicators(data.cost_product);
  //     UnitsVolSold(data.cost_product);
  //     totalCost(data.cost_product);
  //     graphicCostExpenses(data.cost_product);
  //     graphicCostWorkforce(data.cost_workforce);
  //     graphicCostTimeProcess(data.cost_time_process);
  //     graphicCostMaterials(data.cost_materials);
  //   });

  // /* Colors */

  // dynamicColors = () => {
  //   let letters = '0123456789ABCDEF'.split('');
  //   let color = '#';

  //   for (var i = 0; i < 6; i++)
  //     color += letters[Math.floor(Math.random() * 16)];
  //   return color;
  // };

  // getRandomColor = (a) => {
  //   let color = [];
  //   for (i = 0; i < a; i++) color.push(dynamicColors());
  //   return color;
  // };

  // /* Indicadores Generales */

  // generalIndicators = (data) => {
  //   $('#rawMaterial').html(data[0].cost_materials.toLocaleString('es-ES'));
  //   $('#workforce').html(data[0].cost_workforce.toLocaleString('es-ES'));
  //   $('#indirectCost').html(data[0].cost_indirect_cost.toLocaleString('es-ES'));
  //   $('#assignableExpenses').html(
  //     data[0].assignable_expense.toLocaleString('es-ES')
  //   );
  // };

  // /* Ventas */

  // UnitsVolSold = (data) => {
  //   $('#unitsSold').html(data[0].units_sold.toLocaleString('es-ES'));
  //   $('#turnover').html(data[0].turnover.toLocaleString('es-ES'));
  // };

  // /* Costeo Total */

  // totalCost = (data) => {
  //   cost =
  //     parseFloat(data[0].cost_materials) +
  //     parseFloat(data[0].cost_workforce) +
  //     parseFloat(data[0].cost_indirect_cost);
  //   costTotal = cost + parseFloat(data[0].assignable_expense);

  //   $('#costTotal').html(costTotal.toLocaleString('es-ES'));
  //   $('#cost').html(cost.toLocaleString('es-ES'));
  //   $('#payRawMaterial').html(data[0].cost_materials.toLocaleString('es-ES'));
  //   $('#payWorkforce').html(data[0].cost_workforce.toLocaleString('es-ES'));
  //   $('#payIndirectCost').html(
  //     data[0].cost_indirect_cost.toLocaleString('es-ES')
  //   );
  //   $('#payAssignableExpenses').html(
  //     data[0].assignable_expense.toLocaleString('es-ES')
  //   );
  //   $('#commisionSale').html(`${data[0].commision_sale}%`);
  //   $('#profitability').html(`${data[0].profitability}%`);
  // };

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
            label: '',
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
            //label: '# of Tomatoes',
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
