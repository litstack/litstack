<template>
    <apexchart
        class="fj-bar-chart"
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
    variantColor
} from './chart.service';
export default {
    name: 'ChartApexBar',
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
            background: '#fff',

            options: {
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
            },

            series: []
        };
    },
    beforeMount() {
        let variant = variantColor(this.variant);

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
                xaxis: { categories: data.categories }
            });
            this.series = data.series;

            if ('labels' in data) {
                this.$refs.chart.updateOptions({
                    labels: data.labels
                });
            }
        },

        addAreaColor(variant, second = false) {
            let reverse = second ? secondColor(variant) : firstColor(variant);

            this.options.colors.push(reverse); // chart stroke
            this.options.markers.colors.push(reverse); // Inner circle

            this.options.fill.colors.push(reverse); // chart gradient
        }
    }
};
</script>

<style lang="scss" scoped>
.fj-bar-chart {
    margin: 0;
}
</style>
