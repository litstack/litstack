<template>
    <apexchart
        class="lit-area-chart"
        ref="chart"
        type="area"
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
    name: 'ChartApexArea',
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
                    type: 'area',
                    height: '100%',
                    toolbar: {
                        show: false,
                    },
                    animations: {
                        enabled: false,
                    },
                },
                xaxis: {
                    categories: [],
                    labels: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    tooltip: {
                        enabled: false,
                    },
                },
                yaxis: {
                    labels: {
                        formatter: this.format,
                    },
                    show: false,
                },
                dataLabels: {
                    enabled: false,
                },
                //colors: [], // stroke color
                stroke: {
                    curve: 'straight',
                    width: 2,
                },
                markers: {
                    size: [3],
                    colors: [],
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
                        sizeOffset: 3,
                    },
                },
                fill: {
                    //colors: [],
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.5,
                        opacityTo: 0,
                    },
                },
                legend: {
                    show: false,
                },
                grid: {
                    show: false,
                },
                tooltip: {
                    enabled: true,
                    theme: 'dark',
                },
                labels: [],
            },

            series: [],
        };
    },
    beforeMount() {
        let variant = variantColor(this.variant);

        this.resetColors();

        this.background = variant;

        this.makeArea(variant);
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

        resetColors() {
            this.options.colors = [];
            this.options.markers.colors = [];
            this.options.markers.strokeColors = [];
            this.options.fill.colors = [];
            this.background = null;
        },
    },
};
</script>

<style lang="scss" scoped>
.lit-area-chart {
    margin: 0 -10px 0 -12px;
}
</style>
