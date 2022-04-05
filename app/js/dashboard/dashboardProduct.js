/* DashboardProducts */
$(document).ready(function () {
  id_product = sessionStorage.getItem('idProduct');
  $.ajax({
    type: 'GET',
    url: `/api/dashboardPricesProducts/${id_product}`,
    success: function (r) {
      $('#rawMaterial').html(r[0].cost_materials.toLocaleString('es-ES'));
      $('#workforce').html(r[0].cost_workforce.toLocaleString('es-ES'));
      $('#indirectCost').html(r[0].cost_indirect_cost.toLocaleString('es-ES'));
      $('#assignableExpenses').html(
        r[0].assignable_expense.toLocaleString('es-ES')
      );

      // Ventas
      $('#unitsSold').html(r[0].units_sold.toLocaleString('es-ES'));
      $('#turnover').html(r[0].turnover.toLocaleString('es-ES'));

      // Costeo total
      cost =
        r[0].cost_materials + r[0].cost_workforce + r[0].cost_indirect_cost;

      costTotal = cost + r[0].assignable_expense;

      $('#costTotal').html(costTotal.toLocaleString('es-ES'));
      $('#cost').html(cost.toLocaleString('es-ES'));
      $('#payRawMaterial').html(r[0].cost_materials.toLocaleString('es-ES'));
      $('#payWorkforce').html(r[0].cost_workforce.toLocaleString('es-ES'));
      $('#payIndirectCost').html(
        r[0].cost_indirect_cost.toLocaleString('es-ES')
      );
      $('#payAssignableExpenses').html(
        r[0].assignable_expense.toLocaleString('es-ES')
      );
      $('#commisionSale').html(r[0].commision_sale);
      $('#profitability').html(r[0].profitability);

      totalTimeProcess = {};
      costWorkforce = {};
      costRawMaterials = {};

      for (i = 1; i < r.length; i++) {
        if (r[i].totalTime != null) {
          // Total tiempo procesos
          data_total_time_process = r[i];
          totalTimeProcess['tp' + i] = data_total_time_process;
        } else if (r[i].workforce != null) {
          // Costos mano de obra
          data_cost_workforce = r[i];
          costWorkforce['wf' + [i]] = data_cost_workforce;
        } else {
          // Costos materia prima
          data_cost_materials = r[i];
          costRawMaterials['rm' + i] = data_cost_materials;
        }
      }
      sessionStorage.setItem(
        'dataTotalTimeProcess',
        JSON.stringify(totalTimeProcess)
      );
      sessionStorage.setItem(
        'dataCostRawMaterials',
        JSON.stringify(costRawMaterials)
      );
      sessionStorage.setItem(
        'dataCostWorkforce',
        JSON.stringify(costWorkforce)
      );
    },
  });

  /* Costo del producto */

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
          data: [38736, 1290100, 2139, 5500],
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

  /* Mano de Obra */
  //const cmo = document.getElementById('charWorkForce').getContext('2d');
  costWorkforce = sessionStorage.getItem('dataCostWorkforce');
  dataWorkforce = JSON.parse(costWorkforce);
  var cmo = document.getElementById('charWorkForce');
  // for (i = 0; i < Object.keys(dataWorkforce).length; i++) {
  //   label = dataWorkforce['wf'].process;
  // }
  var charWorkForce = new Chart(cmo, {
    type: 'pie',
    data: {
      labels: [
        dataWorkforce['wf6'].process,
        dataWorkforce['wf7'].process,
        dataWorkforce['wf8'].process,
        dataWorkforce['wf9'].process,
        dataWorkforce['wf10'].process,
        dataWorkforce['wf11'].process,
      ],
      datasets: [
        {
          //label: '# of Tomatoes',
          data: [
            dataWorkforce['wf6'].workforce,
            dataWorkforce['wf7'].workforce,
            dataWorkforce['wf8'].workforce,
            dataWorkforce['wf9'].workforce,
            dataWorkforce['wf10'].workforce,
            dataWorkforce['wf11'].workforce,
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

  /* Tiempo de Proceso del producto */
  debugger;

  timeProcess = sessionStorage.getItem('dataTotalTimeProcess');
  dataTimeProcess = JSON.parse(timeProcess);
  /*for (i = 1; i <= Object.keys(dataTimeProcess).length; i++) {
    label['tp'] = dataTimeProcess['tp' + i].process;
  }*/
  var cmo = document.getElementById('charTimeProcess');
  var charWorkForce = new Chart(cmo, {
    type: 'doughnut',
    data: {
      labels: [
        // dataTimeProcess.process,
        dataTimeProcess['tp2'].process,
        dataTimeProcess['tp3'].process,
        dataTimeProcess['tp4'].process,
        dataTimeProcess['tp5'].process,
      ],
      datasets: [
        {
          //label: '# of Tomatoes',
          data: [
            dataTimeProcess['tp1'].totalTime,
            dataTimeProcess['tp2'].totalTime,
            dataTimeProcess['tp3'].totalTime,
            dataTimeProcess['tp4'].totalTime,
            dataTimeProcess['tp5'].totalTime,
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

  /* Costos de la materia prima */
  rawMaterial = sessionStorage.getItem('dataCostRawMaterials');
  dataRawMaterial = JSON.parse(rawMaterial);
  const cmc = document.getElementById('chartMaterialsCosts').getContext('2d');

  const chartMaterials = new Chart(cmc, {
    type: 'bar',
    data: {
      labels: [
        dataRawMaterial['rm12'].material,
        dataRawMaterial['rm13'].material,
        dataRawMaterial['rm14'].material,
        dataRawMaterial['rm15'].material,
        dataRawMaterial['rm16'].material,
      ],
      datasets: [
        {
          data: [
            dataRawMaterial['rm12'].totalCostMaterial,
            dataRawMaterial['rm13'].totalCostMaterial,
            dataRawMaterial['rm14'].totalCostMaterial,
            dataRawMaterial['rm15'].totalCostMaterial,
            dataRawMaterial['rm16'].totalCostMaterial,
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
});
