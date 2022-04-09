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
    $('#product').html(data[0].product);

    $('#rawMaterial').html(
      `$ ${data[0].cost_materials.toLocaleString('es-ES')}`
    );
    percentRawMaterial = (data[0].cost_materials / data[0].price) * 100;
    $('#percentRawMaterial').html(`${percentRawMaterial.toFixed(2)} %`);

    $('#workforce').html(`$ ${data[0].cost_workforce.toLocaleString('es-ES')}`);
    percentWorkforce = (data[0].cost_workforce / data[0].price) * 100;
    $('#percentWorkforce').html(`${percentWorkforce.toFixed(2)} %`);

    $('#indirectCost').html(
      `$ ${data[0].cost_indirect_cost.toLocaleString('es-ES')}`
    );
    percentIndirectCost = (data[0].cost_indirect_cost / data[0].price) * 100;
    $('#percentIndirectCost').html(`${percentIndirectCost.toFixed(2)} %`);

    $('#assignableExpenses').html(`$ ${data[0].assignable_expense.toFixed(2)}`);
    percentAssignableExpenses =
      (data[0].assignable_expense / data[0].price) * 100;
    $('#percentAssignableExpenses').html(
      `${percentAssignableExpenses.toFixed(2)} %`
    );
  };

  /* Ventas */

  UnitsVolSold = (data) => {
    $('#unitsSold').html(data[0].units_sold.toLocaleString('es-ES'));
    $('#turnover').html(`$ ${data[0].turnover.toLocaleString('es-ES')}`);
    $('#recomendedPrice').html(`$ ${data[0].price.toLocaleString('es-ES')}`);
  };

  /* Costeo Total */

  totalCost = (data) => {
    cost =
      parseFloat(data[0].cost_materials) +
      parseFloat(data[0].cost_workforce) +
      parseFloat(data[0].cost_indirect_cost);
    costTotal = cost + parseFloat(data[0].assignable_expense);

    $('#salesPrice').html(`$ ${data[0].price.toLocaleString('es-ES')}`);
    $('#costTotal').html(`$ ${costTotal.toLocaleString('es-ES')}`);
    $('#cost').html(`$ ${cost.toLocaleString('es-ES')}`);
    $('#payRawMaterial').html(
      `$ ${data[0].cost_materials.toLocaleString('es-ES')}`
    );
    $('#payWorkforce').html(
      `$ ${data[0].cost_workforce.toLocaleString('es-ES')}`
    );
    $('#payIndirectCost').html(
      `$ ${data[0].cost_indirect_cost.toLocaleString('es-ES')}`
    );
    $('#payAssignableExpenses').html(
      `$ ${data[0].assignable_expense.toFixed(2)}`
    );

    costCommissionSale = data[0].price * (data[0].commission_sale / 100);

    $('#commisionSale').html(
      `$ ${Math.round(costCommissionSale).toLocaleString('es-ES')} (` +
        `${data[0].commission_sale}%)`
    );

    costProfitability = data[0].price * (data[0].profitability / 100);
    $('#profitability').html(
      `$ ${Math.round(costProfitability).toLocaleString('es-ES')} (` +
        `${data[0].profitability}%)`
    );
  };
});
