<template>
    <apexchart
        class="lit-bar-chart"
        ref="chart"
        type="bar"
        :options="options"
        :series="series"
        height="100%"
    ></apexchart>
</template>

<script>
import {
    PRIMARY,
    SECONDARY,
    WHITE,
    firstColor,
    secondColor,
    variantColor,
} from './chart.service';
export default {
    name: 'ChartApexBar',
    props: {
        variant: {
            type: String,
            default: 'white',
        },
        type: {
            type: String,
            required: true,
        },
        format: {},
    },
    data() {
        return {
            background: '#fff',

            options: {
                chart: {
                    type: 'bar',
                    height: '100%',
                    toolbar: {
                        show: false,
                    },
                    animations: {
                        enabled: false,
                    },
                },
                grid: {
                    borderColor: 'red',
                    strokeDashArray: 2,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: 20,
                        endingShape: 'flat',
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    // stroke can be used as a margin between bars when set transparent
                    show: true,
                    width: 2,
                    colors: ['transparent'],
                },
                xaxis: {
                    labels: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                    },
                    categories: [],
                },
                yaxis: {
                    title: {
                        text: undefined,
                    },
                    labels: {
                        show: true,
                        style: {
                            colors: ['pink'],
                            cssClass: 'apexcharts-yaxis-label',
                            //   fontSize: '12px',
                            //   fontFamily: 'Helvetica, Arial, sans-serif',
                            //   fontWeight: 400,
                        },
                    },
                },
                colors: [], // stroke color
                fill: {
                    opacity: 1,
                    colors: [],
                },
                legend: {
                    show: false,
                },
                tooltip: {
                    enabled: true,
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return val + ' K';
                        },
                    },
                },
                markers: {
                    size: [3],
                    colors: ['white'],
                },
            },

            series: [],
        };
    },
    beforeMount() {
        let variant = variantColor(this.variant);

        this.background = variant;

        this.makeArea(variant);
        this.setGridColor(variant);
    },
    methods: {
        makeArea(variant) {
            this.addAreaColor(variant);
            this.addAreaColor(variant, true);
        },

        update(data) {
            this.$refs.chart.updateOptions({
                xaxis: { categories: data.categories },
            });
            this.series = data.series;

            if ('labels' in data) {
                this.$refs.chart.updateOptions({
                    labels: data.labels,
                });
            }
        },

        addAreaColor(variant, second = false) {
            let reverse = second ? secondColor(variant) : firstColor(variant);

            this.options.colors.push(reverse); // chart stroke
            this.options.markers.colors.push(reverse); // Inner circle

            this.options.fill.colors.push(reverse); // chart gradient
        },

        setGridColor(variant) {
            if (variant == WHITE) {
                this.options.grid.borderColor = 'rgba(125, 125, 125, 0.4)';
            } else {
                this.options.grid.borderColor = 'rgba(255, 255, 255, 0.4)';
            }
        },
    },
};
</script>

<style lang="scss">
.lit-bar-chart {
    margin: 0;
}
.lit-chart.primary,
.lit-chart.secondary {
    .apexcharts-yaxis-label {
        tspan {
            fill: rgba(255, 255, 255, 0.4);
        }
    }
}

.lit-chart.white {
    .apexcharts-yaxis-label {
        tspan {
            fill: rgba(125, 125, 125, 0.4);
        }
    }
}
</style>
