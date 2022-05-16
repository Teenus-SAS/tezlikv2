$(document).ready(function () {
  /* ACCESOS DE USUARIO */
  $.ajax({
    type: 'POST',
    url: `/api/userAccess`,
    success: function (resp) {
      let acces = {
        createProducts: resp.create_product,
        createMaterials: resp.create_materials,
        createMachines: resp.create_machines,
        createProcess: resp.create_process,
        productsMaterials: resp.product_materials,
        productsProcess: resp.product_process,
        factoryLoad: resp.factory_load,
        servicesExternal: resp.external_service,
        payroll: resp.payroll_load,
        generalExpenses: resp.expense,
        distributionExpenses: resp.expense_distribution,
        users: resp.user,
      };

      $.each(acces, (index, value) => {
        if (value === 0) {
          $(`.${index}`).remove();
        }
      });
    },
  });
});
