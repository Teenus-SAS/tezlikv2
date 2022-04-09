/* Tiempo de procesos */
graphicTimeProcessByProduct = (data) => {
  product = [];
  // enlistmentTime = [];
  // operationTime = [];
  totalTime = [];

  data.length > 10 ? (count = 10) : (count = data.length);

  for (i = 0; i < count; i++) {
    product.push(data[i].product);
    totalTime.push(data[i].totalTime);
  }

  const cmc = document.getElementById('chartProductCosts');
  const chartProductCosts = new Chart(cmc, {
    type: 'bar',
    data: {
      labels: product,
      formatter: function (value, context) {
        return context.chart.data.labels[context.dataIndex];
      },
      datasets: [
        {
          //labels: ['Tiempo Enlistamiento', 'Tiempo Operacion'],
          data: totalTime,
          backgroundColor: getRandomColor(count),
          borderWidth: 1,
        },
      ],
    },
    //plugins: [ChartDataLabels],
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

/* Mano de obra */
graphicWorkforce = (data) => {
  process = [];
  minuteValue = [];
  totalCost = 0;

  for (let i in data) {
    process.push(data[i].process);
    minuteValue.push(data[i].minute_value);
    totalCost = totalCost + minuteValue[i];
  }

  $('#totalCostWorkforce').html(totalCost.toLocaleString('es-ES'));

  const cmc = document.getElementById('charWorkForceGeneral');
  const charWorkForceGeneral = new Chart(cmc, {
    type: 'doughnut',
    data: {
      labels: process,
      formatter: function (value, context) {
        return context.chart.data.labels[context.dataIndex];
      },
      datasets: [
        {
          data: minuteValue,
          backgroundColor: getRandomColor(data.length),
          //borderColor: [],
          borderWidth: 1,
        },
      ],
    },
    plugins: [ChartDataLabels],
    options: {
      plugins: {
        legend: {
          display: false,
        },
      },
    },
  });
};

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
      formatter: function (value, context) {
        return context.chart.data.labels[context.dataIndex];
      },
      datasets: [
        {
          data: costMinute,
          backgroundColor: getRandomColor(data.length),
          //borderColor: [],
          borderWidth: 1,
        },
      ],
    },
    plugins: [ChartDataLabels],
    options: {
      plugins: {
        legend: {
          display: false,
        },
      },
    },
  });
};

/* Gastos generales */

graphicGeneralCost = (data) => {
  expenseCount = [];
  totalExpense = 0;

  for (i = 0; i < 3; i++) {
    expenseCount.push(data[i].expenseCount);
    totalExpense = totalExpense + data[i].expenseCount;
  }
  $('#totalCost').html(totalExpense.toLocaleString('es-ES'));

  /* Grafico */
  var cmo = document.getElementById('charExpensesGenerals');
  var charExpensesGenerals = new Chart(cmo, {
    type: 'doughnut',
    data: {
      labels: [
        'Operacionales de administración',
        'Gastos de Ventas',
        'No operacionales',
      ],
      datasets: [
        {
          data: expenseCount,
          backgroundColor: getRandomColor(3),
          //borderColor: [],
          borderWidth: 1,
        },
      ],
    },
    plugins: [ChartDataLabels],
    options: {
      plugins: {
        legend: {
          display: false,
        },
      },
    },
  });
};

/* Indicadores globales */

graphicProfit = (data) => {
  const cmo = document.getElementById('charExpensesGenerals');
  const charExpensesGenerals = new Chart(cmo, {
    type: 'doughnut',
    data: {
      labels: ['Utilidad'],
      datasets: [
        {
          data: [
            data['expenseCount51'],
            data['expenseCount52'],
            data['expenseCount53'],
          ],

          backgroundColor: getRandomColor(3),
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
