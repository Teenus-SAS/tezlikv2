$(document).ready(function () {
  id_product = sessionStorage.getItem('idProduct');

  fetch(`/api/dashboardPricesProducts/${id_product}`)
    .then((response) => response.text())
    .then((data) => {
      data = JSON.parse(data);
      generalIndicators(data.cost_product);
      UnitsVolSold(data.cost_product);
      totalCost(data.cost_product);
      graphicCostExpenses(data.cost_product);
      graphicCostWorkforce(data.cost_workforce);
      graphicCostTimeProcess(data.cost_time_process);
      graphicCostMaterials(data.cost_materials);
    });

  /* Colors */

  dynamicColors = () => {
    let letters = '0123456789ABCDEF'.split('');
    let color = '#';

    for (var i = 0; i < 6; i++)
      color += letters[Math.floor(Math.random() * 16)];
    return color;
  };

  getRandomColor = (a) => {
    let color = [];
    for (i = 0; i < a; i++) color.push(dynamicColors());
    return color;
  };

  /* Indicadores Generales */

  generalIndicators = (data) => {
    $('#rawMaterial').html(data[0].cost_materials.toLocaleString('es-ES'));
    $('#workforce').html(data[0].cost_workforce.toLocaleString('es-ES'));
    $('#indirectCost').html(data[0].cost_indirect_cost.toLocaleString('es-ES'));
    $('#assignableExpenses').html(
      data[0].assignable_expense.toLocaleString('es-ES')
    );
  };

  /* Ventas */

  UnitsVolSold = (data) => {
    $('#unitsSold').html(data[0].units_sold.toLocaleString('es-ES'));
    $('#turnover').html(data[0].turnover.toLocaleString('es-ES'));
  };

  /* Costeo Total */

  totalCost = (data) => {
    cost =
      parseFloat(data[0].cost_materials) +
      parseFloat(data[0].cost_workforce) +
      parseFloat(data[0].cost_indirect_cost);
    costTotal = cost + parseFloat(data[0].assignable_expense);

    $('#costTotal').html(costTotal.toLocaleString('es-ES'));
    $('#cost').html(cost.toLocaleString('es-ES'));
    $('#payRawMaterial').html(data[0].cost_materials.toLocaleString('es-ES'));
    $('#payWorkforce').html(data[0].cost_workforce.toLocaleString('es-ES'));
    $('#payIndirectCost').html(
      data[0].cost_indirect_cost.toLocaleString('es-ES')
    );
    $('#payAssignableExpenses').html(
      data[0].assignable_expense.toLocaleString('es-ES')
    );
    $('#commisionSale').html(`${data[0].commision_sale}%`);
    $('#profitability').html(`${data[0].profitability}%`);
  };
});
