<template>
    <apexchart
        class="lit-bar-chart"
        ref="chart"
        type="donut"
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
    COLORS,
    firstColor,
    secondColor,
    variantColor,
} from './chart.service';
export default {
    name: 'ChartApexDonut',
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
                    height: '100%',
                    toolbar: {
                        show: false,
                    },
                    animations: {
                        enabled: false,
                    },
                    type: 'radialBar',
                },
                fill: {
                    type: 'solid',
                    opacity: 1,
                    colors: [],
                },
                labels: [],
                stroke: {
                    lineCap: 'round',
                },
                legend: {
                    show: false,
                },
                dataLabels: {
                    enabled: false,
                },
                colors: [],
                tooltip: {
                    enabled: true,
                    enabled: true,
                    theme: 'dark',
                    fillSeriesColor: false,
                },
                plotOptions: {
                    pie: {
                        customScale: 0.9,
                        donut: {
                            background: 'transparent', // inner circle background
                            size: '94%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    fontSize: '1rem',
                                    fontFamily: 'Inter',
                                    fontWeight: 400,
                                    color: 'white',
                                },
                                value: {
                                    show: true,
                                    fontSize: '2rem',
                                    fontFamily: 'Inter',
                                    fontWeight: 700,
                                    color: 'white',
                                    offsetY: 4,
                                },
                            },
                        },
                    },
                },
            },

            series: [],
        };
    },
    beforeMount() {
        let variant = variantColor(this.variant);

        this.background = variant;

        this.makeDonut(variant);
    },
    methods: {
        makeDonut(variant) {
            this.options.fill.colors = [];
            if (variant == WHITE) {
                for (let i = 0; i < COLORS.length; i++) {
                    this.options.fill.colors.push(COLORS[i]);
                    this.options.colors.push(COLORS[i]);
                }
            } else {
                let whites = [
                    'rgba(255,255,255,.1',
                    'rgba(255,255,255,1',
                    'rgba(255,255,255,.45',
                ];
                for (let i = 0; i < whites.length; i++) {
                    this.options.fill.colors.push(whites[i]);
                    this.options.colors.push(whites[i]);
                }
            }

            let fontColor = variant == WHITE ? 'black' : 'white';

            this.options.plotOptions.pie.donut.labels.total.color = fontColor;
            this.options.plotOptions.pie.donut.labels.value.color = fontColor;

            this.options.stroke.colors = ['transparent'];
        },

        update(data) {
            this.series = data.series;

            if ('labels' in data) {
                this.$refs.chart.updateOptions({
                    labels: data.labels,
                });
            }
        },
    },
};
</script>

<style lang="scss">
.lit-bar-chart {
    margin: 0;
}
</style>
