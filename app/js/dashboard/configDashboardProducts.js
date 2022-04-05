$(document).ready(function () {
  //   id_product = sessionStorage.getItem('idProduct');
  //   $.ajax({
  //     type: 'GET',
  //     url: `/api/dashboardPricesProducts/${id_product}`,
  //     success: function (r) {
  //       $('#rawMaterial').html(r[0].cost_materials.toLocaleString('es-ES'));
  //       $('#workforce').html(r[0].cost_workforce.toLocaleString('es-ES'));
  //       $('#indirectCost').html(r[0].cost_indirect_cost.toLocaleString('es-ES'));
  //       $('#assignableExpenses').html(
  //         r[0].assignable_expense.toLocaleString('es-ES')
  //       );
  //       // Ventas
  //       $('#unitsSold').html(r[0].units_sold.toLocaleString('es-ES'));
  //       $('#turnover').html(r[0].turnover.toLocaleString('es-ES'));
  //       // Costeo total
  //       cost =
  //         r[0].cost_materials + r[0].cost_workforce + r[0].cost_indirect_cost;
  //       costTotal = cost + r[0].assignable_expense;
  //       $('#costTotal').html(costTotal.toLocaleString('es-ES'));
  //       $('#cost').html(cost.toLocaleString('es-ES'));
  //       $('#payRawMaterial').html(r[0].cost_materials.toLocaleString('es-ES'));
  //       $('#payWorkforce').html(r[0].cost_workforce.toLocaleString('es-ES'));
  //       $('#payIndirectCost').html(
  //         r[0].cost_indirect_cost.toLocaleString('es-ES')
  //       );
  //       $('#payAssignableExpenses').html(
  //         r[0].assignable_expense.toLocaleString('es-ES')
  //       );
  //       $('#commisionSale').html(r[0].commision_sale);
  //       $('#profitability').html(r[0].profitability);
  //       totalTimeProcess = {};
  //       costWorkforce = {};
  //       costRawMaterials = {};
  //       for (i = 1; i < r.length; i++) {
  //         if (r[i].totalTime != null) {
  //           // Total tiempo procesos
  //           data_total_time_process = r[i];
  //           totalTimeProcess['tp' + i] = data_total_time_process;
  //         } else if (r[i].workforce != null) {
  //           // Costos mano de obra
  //           data_cost_workforce = r[i];
  //           costWorkforce['wf' + [i]] = data_cost_workforce;
  //         } else {
  //           // Costos materia prima
  //           data_cost_materials = r[i];
  //           costRawMaterials['rm' + i] = data_cost_materials;
  //         }
  //       }
  //       sessionStorage.setItem(
  //         'dataTotalTimeProcess',
  //         JSON.stringify(totalTimeProcess)
  //       );
  //       sessionStorage.setItem(
  //         'dataCostRawMaterials',
  //         JSON.stringify(costRawMaterials)
  //       );
  //       sessionStorage.setItem(
  //         'dataCostWorkforce',
  //         JSON.stringify(costWorkforce)
  //       );
  //     },
  //   });
});
