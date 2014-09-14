$(function () {

    if (!$('#workshop-session-chart').length) {
        return false;
    }

    area();

    $(window).resize(App.debounce(area, 250));

});

function area() {
    $('#workshop-session-chart').empty();

    Morris.Area({
        element: 'workshop-session-chart',
        data: [
            {period: '2010 Q1', achieved: 90, canceled: 10, unknown: 0},
            {period: '2010 Q2', achieved: 95, canceled: 15, unknown: 0},
            {period: '2011 Q3', achieved: 100, canceled: 20, unknown: 0},
            {period: '2011 Q4', achieved: 89, canceled: 30, unknown: 20},
            {period: '2013 Q1', achieved: 79, canceled: 0, unknown: 1},
            {period: '2013 Q2', achieved: 70, canceled: 0, unknown: 0},
            {period: '2013 Q3', achieved: 130, canceled: 0, unknown: 0},
            {period: '2013 Q4', achieved: 100, canceled: 10, unknown: 20}
        ],
        xkey: 'period',
        ykeys: [ 'achieved', 'canceled', 'unknown'],
        labels: ['Achieved', 'Canceled', 'Unknown'],
        pointSize: 3,
        hideHover: 'auto',
        lineColors: [ "#3fa67a", App.chartColors[0], App.chartColors[1], App.chartColors[2] ]
    });
}