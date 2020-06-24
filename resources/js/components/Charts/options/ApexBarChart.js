export default {
    chart: {
        type: 'bar',
        height: '100%',
        toolbar: {
            show: false
        },
        animations: {
            enabled: false
        }
    },
    grid: {
        strokeDashArray: 2
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: 20,
            endingShape: 'flat'
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        // stroke can be used as a margin between bars when set transparent
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: []
    },
    yaxis: {
        title: {
            text: undefined
        }
    },
    colors: [], // stroke color
    fill: {
        opacity: 1,
        colors: []
    },
    tooltip: {
        y: {
            formatter: function(val) {
                return val + ' K';
            }
        }
    },
    markers: {
        size: [3],
        colors: ['white']
    }
};
