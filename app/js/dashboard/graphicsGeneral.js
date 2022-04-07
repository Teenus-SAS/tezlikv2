/* Tiempo de procesos */

graphicTimeProcessByProduct = (data) => {};

/* Costo carga fabril */

graphicsFactoryLoadSales = (data) => {
  machine = [];
  costMinute = [];

  for (let i in data) {
    machine.push(data[i].machine);
    costMinute.push(data[i].totalCostMinute);
  }

  const cmc = document.getElementById('charFactoryLoadCost');
  const charFactoryLoadCost = new Chart(cmc, {
    type: 'bar',
    data: {
      labels: machine,
      datasets: [
        {
          data: costMinute,
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

  /* Costo mano de obra */
  totalCostMinute = 0;
  for (let i in data) {
    totalCostMinute = totalCostMinute + costMinute[i];
  }
  $('#totalCostWorkforce').html(totalCostMinute.toLocaleString('es-ES'));
};

/* Gastos generales */
graphicGeneralCost = (data) => {
  totalExpense = data.expenseCount51 + data.expenseCount52;
  $('#totalCost').html(totalExpense.toLocaleString('es-ES'));

  /* Grafico */
  process = [];
  minuteValue = [];

  for (let i in data) {
    process.push(data[i].process);
    minuteValue.push(data[i].minute_value);
  }

  var cmo = document.getElementById('charExpensesGenerals');
  var charExpensesGenerals = new Chart(cmo, {
    type: 'doughnut',
    data: {
      labels: process,
      datasets: [
        {
          data: minuteValue,
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
