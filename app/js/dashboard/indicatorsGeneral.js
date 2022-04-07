fetch(`/api/dashboardExpensesGenerals`)
    .then((response) => response.text())
    .then((data) => {
        data = JSON.parse(data);
        generalIndicators(data.expense_value);
        debugger
        /*
        generalSales(data.);
        */
        graphicsFactoryLoad(data.factory_load_minute_value);
        graphicTimeProcessByProduct(data.process_minute_value);
        graphicGeneralCost(data.expense_value);
    });

/* Colors */
dynamicColors = () => {
    let letters = '0123456789ABCDEF'.split('');
    let color = '#';

    for (var i = 0; i < 6; i++) color += letters[Math.floor(Math.random() * 16)];
    return color;
};

getRandomColor = (a) => {
    let color = [];
    for (i = 0; i < a; i++) color.push(dynamicColors());
    return color;
};

/* Indicadores Generales */
generalIndicators = (data) => {
    totalExpense = data.expenseCount51 + data.expenseCount52 + data.expenseCount53;
    $('#generalCost').html(totalExpense.toLocaleString('es-ES'));
};

/* Ventas generales */
generalSales = (data) => {};