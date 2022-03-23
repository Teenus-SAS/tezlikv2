function statisticChart() {
    var t = {
        chart: {
            height: 300,
            type: "bar",
            redrawOnParentResize: !0,
            toolbar: { show: !1 }
        },
        plotOptions: {
            bar: { horizontal: !1, columnWidth: "30%", endingShape: "rounded" }
        },
        tooltip: {
            x: { formatter: function(t) { return "Week " + t } },
            y: { formatter: function(t, e) { return t } }
        },
        dataLabels: { enabled: !1 },
        stroke: { show: !1, width: 1, colors: ["transparent"] },
        grid: { show: !0 },
        series: [{ name: "Dec 2019 - Feb 2020", data: [100, 150, 180, 50, 170, 160, 2, 60, 125, 250, 220] }],
        xaxis: { categories: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11"] },
        legend: { fontFamily: "Nunito Sans, sans-serif", labels: { colors: [colors.primary] } },
        colors: [colors.success],
        fill: { opacity: 1 }
    };
    new ApexCharts(document.querySelector("#stats-chart"), t).render()
}! function(r) {
    "use strict";
    r('[data-plugin="counterup"]').each(function(t, e) { r(this).counterUp({ delay: 50, time: 1200 }) });
    ["comment-scrollbar", "attac-file"].forEach(function(t) { Scrollbar.init(document.getElementById(t)) }), statisticChart()
}(jQuery);