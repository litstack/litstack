<template>
    <apexchart
        ref="chart"
        type="area"
        :options="options"
        :series="series"
        height="100%"
    ></apexchart>
</template>

<script>
export default {
    name: 'ChartApex',
    props: {
        variant: {
            type: String,
            default: 'white'
        }
    },
    data() {
        return {
            primary: '#4951f2',
            secondary: '#6c8199',
            white: '#fff',

            background: '#fff',

            options: {
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
                    categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997],
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
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['red', 'green'], // stroke color
                stroke: {
                    curve: 'straight',
                    width: 3
                },
                markers: {
                    size: 5,
                    colors: [],
                    strokeColors: null,
                    strokeWidth: 3,
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
                }
            },
            series: [
                { name: 'This Week', data: [1, 0, 0, 0, 0, 0, 0] },
                { name: 'Last Week', data: [2, 0, 0, 0, 0, 0, 0] }
                // {
                //     name: 'series-1',
                //     data: [30, 40, 45, 150, 49, 60, 70]
                // },
                // {
                //     color: '',
                //     name: 'series-2',
                //     data: [120, 75, 100, 110, 120, 20, 30]
                // }
            ]
        };
    },
    beforeMount() {
        let color = this[this.variant];
        this.resetColors();
        this.setCardColor(color);
        this.addColor(color);
        this.addColor(color, true);
    },
    methods: {
        // {
        //     labels: [],
        //     values: [{
        //         name: '',
        //         data: []
        //     }]
        // }

        update(data) {
            this.$refs.chart.updateOptions({
                xaxis: { categories: data.categories }
            });
            this.series = data.series;
        },

        resetColors() {
            this.options.colors = [];
            this.options.markers.colors = [];
            this.options.markers.strokeColors = [];
            this.options.fill.colors = [];

            this.background = null;
        },

        addColor(variant, second = false) {
            let reverse = second
                ? this.secondColor(variant)
                : this.firstColor(variant);
            console.log(second, variant, reverse);
            this.options.colors.push(reverse); // chart stroke
            this.options.fill.colors.push(reverse); // chart gradient
            this.options.markers.strokeColors.push(reverse); // Circle border
            this.options.markers.colors.push(this.background); // Inner circle
        },

        setCardColor(variant) {
            this.background = variant;
        },

        firstColor(variant) {
            switch (variant) {
                case this.white:
                    return this.primary;
                    break;
                case this.primary:
                    return this.white;
                    break;
                case this.secondary:
                    return this.white;
                    break;
                default:
                    return this.primary;
                    break;
            }
        },

        secondColor(variant) {
            return variant == this.white
                ? '#ddd'
                : `#${this.lightenDarkenColor(variant.replace('#', ''), -50)}`;
        },
        lightenDarkenColor(col, amt) {
            col = parseInt(col, 16);
            return (
                ((col & 0x0000ff) + amt) |
                ((((col >> 8) & 0x00ff) + amt) << 8) |
                (((col >> 16) + amt) << 16)
            ).toString(16);
        }
    }
};
</script>
