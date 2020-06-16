<template>
    <apexchart
        ref="chart"
        :type="type"
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
        },
        type: {
            type: String,
            required: true
        },
        format: {}
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
                    labels: {
                        formatter: this.format
                    },
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
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true
                                }
                            }
                        }
                    }
                },
                labels: ['Comedy', 'Action', 'SciFi', 'Drama', 'Horror']
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
        let variant = this[this.variant];
        this.resetColors();
        this.setCardColor(variant);
        if (this.type == 'area') this.makeArea(variant);
        if (this.type == 'donut') this.makeDonut(variant);
    },
    methods: {
        // {
        //     labels: [],
        //     values: [{
        //         name: '',
        //         data: []
        //     }]
        // }

        makeDonut(variant) {
            this.options.fill = {
                colors: []
            };
            this.options.colors.push(this.secondColor(variant));
            this.options.fill.colors.push('#70859c');
            this.options.fill.colors.push('#83c2ff');
            this.options.fill.colors.push('#9ff2ae');
            this.options.fill.colors.push('#83c2ff'); // chart fill color
        },

        makeArea(variant) {
            this.addAreaColor(variant);
            this.addAreaColor(variant, true);
        },

        update(data) {
            this.$refs.chart.updateOptions({
                xaxis: { categories: data.categories }
            });
            this.series = data.series;

            if ('labels' in data) {
                this.$refs.chart.updateOptions({
                    labels: data.labels
                });
            }
        },

        resetColors() {
            this.options.colors = [];
            this.options.markers.colors = [];
            this.options.markers.strokeColors = [];
            this.options.fill.colors = [];

            this.background = null;
        },

        addAreaColor(variant, second = false) {
            let reverse = second
                ? this.secondColor(variant)
                : this.firstColor(variant);

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
