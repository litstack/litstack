export default {
    chart: {
        height: '100%',
        toolbar: {
            show: false
        },
        animations: {
            enabled: false
        }
    },
    xaxis: {
        categories: [],
        labels: {
            show: false
        },
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false
        }
    },
    yaxis: {
        labels: {
            //formatter: this.format
        },
        show: false
    },
    dataLabels: {
        enabled: false
    },
    colors: [], // stroke color
    stroke: {
        curve: 'straight',
        width: 2
    },
    markers: {
        size: [3],
        colors: ['white'],
        strokeColors: null,
        strokeWidth: 0,
        strokeOpacity: 0.9,
        strokeDashArray: 0,
        fillOpacity: 1,
        discrete: [],
        shape: 'circle',
        radius: 2,
        offsetX: 0,
        offsetY: 0,
        onClick: undefined,
        onDblClick: undefined,
        showNullDataPoints: true,
        hover: {
            size: undefined,
            sizeOffset: 3
        }
    },
    fill: {
        colors: [],
        type: 'gradient',
        gradient: {
            opacityFrom: 0.5,
            opacityTo: 0
        }
    },
    legend: {
        show: false
    },
    grid: {
        show: false
    },

    labels: []
};
