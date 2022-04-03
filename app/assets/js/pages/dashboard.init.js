function statsChart() {
    var e = { chart: { type: "area", height: 45, width: 90, sparkline: { enabled: !0 }, parentHeightOffset: 0, toolbar: { show: !1 } }, colors: [colors.primary], markers: { size: 0 }, tooltip: { theme: "dark", fixed: { enabled: !1 }, x: { show: !1 }, y: { title: { formatter: function(e) { return "" } } }, marker: { show: !1 } }, fill: { type: "gradient", gradient: { type: "vertical", shadeIntensity: 1, inverseColors: !1, opacityFrom: .45, opacityTo: .05, stops: [45, 100] } }, stroke: { width: 2, curve: "smooth" }, series: [{ data: [25, 66, 41, 85, 63, 25, 44, 12, 36, 9, 54] }] };
    new ApexCharts(document.querySelector("#t-rev"), e).render()
}

function statsChart2() {
    var e = { chart: { type: "area", height: 45, width: 90, sparkline: { enabled: !0 }, parentHeightOffset: 0, toolbar: { show: !1 } }, colors: [colors.orange], markers: { size: 0 }, tooltip: { theme: "dark", fixed: { enabled: !1 }, x: { show: !1 }, y: { title: { formatter: function(e) { return "" } } }, marker: { show: !1 } }, fill: { type: "gradient", gradient: { type: "vertical", shadeIntensity: 1, inverseColors: !1, opacityFrom: .45, opacityTo: .05, stops: [45, 100] } }, stroke: { width: 2, curve: "smooth" }, series: [{ data: [25, 66, 41, 85, 63, 25, 44, 12, 36, 9, 54] }] };
    new ApexCharts(document.querySelector("#t-order"), e).render()
}

function statsChart3() {
    var e = { chart: { type: "area", height: 45, width: 90, sparkline: { enabled: !0 }, parentHeightOffset: 0, toolbar: { show: !1 } }, colors: [colors.success], markers: { size: 0 }, tooltip: { theme: "dark", fixed: { enabled: !1 }, x: { show: !1 }, y: { title: { formatter: function(e) { return "" } } }, marker: { show: !1 } }, fill: { type: "gradient", gradient: { type: "vertical", shadeIntensity: 1, inverseColors: !1, opacityFrom: .45, opacityTo: .05, stops: [45, 100] } }, stroke: { width: 2, curve: "smooth" }, series: [{ data: [25, 66, 41, 85, 63, 25, 44, 12, 36, 9, 54] }] };
    new ApexCharts(document.querySelector("#t-user"), e).render()
}

function statsChart4() {
    var e = { chart: { type: "area", height: 45, width: 90, sparkline: { enabled: !0 }, parentHeightOffset: 0, toolbar: { show: !1 } }, colors: [colors.warning], markers: { size: 0 }, tooltip: { theme: "dark", fixed: { enabled: !1 }, x: { show: !1 }, y: { title: { formatter: function(e) { return "" } } }, marker: { show: !1 } }, fill: { type: "gradient", gradient: { type: "vertical", shadeIntensity: 1, inverseColors: !1, opacityFrom: .45, opacityTo: .05, stops: [45, 100] } }, stroke: { width: 2, curve: "smooth" }, series: [{ data: [25, 66, 41, 85, 63, 25, 44, 12, 36, 9, 54] }] };
    new ApexCharts(document.querySelector("#t-visitor"), e).render()
}

function statisticChart() {
    var e = {
        chart: {
            height: 270,
            type: "bar",
            //redrawOnParentResize: !0,
            toolbar: { show: !1 }
        },
        plotOptions: {
            bar: {
                horizontal: !1,
                columnWidth: "30%",
                /* endingShape: "rounded" */
            }
        },
        tooltip: { y: { formatter: function(e) {} } },
        dataLabels: { enabled: !1 },
        stroke: { show: !1, width: 1, colors: ["transparent"] },
        grid: { show: !0 },
        series: [{
                name: "Costos",
                data: [35000, 84000, 55000, 134000]
            }
            /* , {
                        name: "Previous Year",
                        data: [52, 76, 85, 101, 98, 87, 120, 54, 40, 70, 110, 65]
                    } */
        ],
        xaxis: {
            categories: ["Mano de Obra", "Materia Prima", "Costos Indirectos", "Gastos Generales"]
        },
        legend: {
            fontFamily: "Nunito Sans, sans-serif",
            itemMargin: {
                vertical: 10,
                horizontal: 10
            },
            labels: {
                colors: ["#505d69"]
            }
        },
        colors: [colors.primary, colors.success, colors.warning],
        fill: { opacity: 1 }
    };

    new ApexCharts(document.querySelector("#stats-chart"), e).render()
}

function quarterlyChart() {
    var e = { chart: { height: 275, type: "bar", stacked: !0, toolbar: { show: !1 } }, dataLabels: { enabled: !1 }, stroke: { show: !1, width: 1, colors: ["transparent"] }, grid: { show: !0 }, legend: { fontFamily: "Nunito Sans, sans-serif", itemMargin: { vertical: 10, horizontal: 10 } }, series: [{ name: "Quarter 1", data: [30, 50, 60, 20] }, { name: "Quarter 2", data: [40, 30, 40, 40] }, { name: "Quarter 3", data: [50, 80, 60, 50] }, { name: "Quarter 4", data: [60, 90, 70, 60] }], xaxis: { categories: ["Q1", "Q2", "Q3", "Q4"] }, colors: [colors.primary, colors.info, colors.warning, colors.teal], fill: { opacity: 1 } };
    new ApexCharts(document.querySelector("#quartly-sale"), e).render()
}

function todaySaleChart() {
    var e = { type: "doughnut", data: { labels: ["Direct sales", "Referral sales", "Afilliate sales", "Indirect sales"], datasets: [{ label: "Doughnut chart", data: [50, 40, 30, 10], backgroundColor: [colors.primary, colors.warning, colors.info, colors.orange], borderWidth: 0 }] }, options: { responsive: !0, maintainAspectRatio: !1, legend: { position: "top", display: !1 }, cutoutPercentage: 70 } };
    new Chart(document.getElementById("total-sale"), e)
}

function statsPerWeekChart(e, t, r) {
    var a, o = [];
    if (o = "monthly" === e ? [{ name: "Monthly", data: [210, 200, 100, 50, 40, 150, 700, 650, 400, 300, 250, 200] }] : [{ name: "Weekly", data: [20, 15, 60, 21, 40, 23, 35, 50, 80, 70, 55, 70] }], !t) return a = { chart: { height: 238, type: "area", toolbar: { show: !1 } }, dataLabels: { enabled: !1 }, stroke: { width: 2, curve: "smooth" }, series: o, xaxis: { categories: ["1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th", "11th", "12th"], tooltip: { enabled: !1, offsetX: 0 } }, colors: [colors.success], fill: { type: "gradient", gradient: { type: "vertical", shadeIntensity: 1, inverseColors: !1, opacityFrom: .45, opacityTo: .05, stops: [45, 100] } }, tooltip: { theme: "dark", x: { show: !1 }, marker: { show: !1 } } }, (r = new ApexCharts(document.querySelector("#sales-order"), a)).render(), r;
    r.updateSeries(o)
}

function todayRevenue() {
    var e = { type: "doughnut", data: { datasets: [{ data: [600, 1400], backgroundColor: [colors.white, "rgba(255,255,255,0.3)"], hoverBackgroundColor: [colors.white, "rgba(255,255,255,0.3)"], borderWidth: 0 }] }, options: { cutoutPercentage: 94, responsive: !0, maintainAspectRatio: !1, scales: { xAxes: [{ display: !1 }], yAxes: [{ display: !1 }] }, legend: { display: !1 }, tooltips: { enabled: !1 } } };
    new Chart(document.getElementById("today-revenue"), e)
}

$(function() {
    var e;
    statsChart(), statsChart2(), statsChart3(), statsChart4(), statisticChart(), quarterlyChart(), todaySaleChart(), e = statsPerWeekChart("weekly", !1, e), todayRevenue(), $(".earningTabs button").on("click", function() { $(this).addClass("btn-primary").removeClass("btn-outline-primary"), $(this).siblings().removeClass("btn-primary").addClass("btn-outline-primary"), statsPerWeekChart($(this).attr("data-type"), !0, e) })
}(jQuery));