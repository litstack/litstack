<template>
    <apexchart
        class="fj-bar-chart"
        ref="chart"
        type="radialBar"
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
    name: 'ChartApexProgress',
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
                    height: '100%',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: false
                    },
                    type: 'radialBar'
                },

                // dataLabels: {
                //     enabled: false
                // },
                // colors: ['red', 'green'], // stroke color
                // stroke: {
                //     curve: 'straight',
                //     width: 2
                // },

                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '70%',
                            background: 'transparent'
                        },
                        track: {
                            show: true,
                            margin: 5,
                            background: '#f2f2f2',
                            strokeWidth: '10%'
                        }
                    }
                },
                labels: [],
                stroke: {
                    lineCap: 'round'
                }
            },

            series: []
        };
    },
    beforeMount() {
        let variant = variantColor(this.variant);

        this.background = variant;

        this.makeRadialBar(variant);
    },
    methods: {
        makeRadialBar(variant) {
            this.options.fill = {
                colors: []
            };

            this.options.fill.colors.push('red');

            let fontColor = variant == WHITE ? 'black' : 'white';

            // this.options.plotOptions.pie.donut.labels.total.color = fontColor;
            // this.options.plotOptions.pie.donut.labels.value.color = fontColor;

            // this.options.stroke.colors = ['transparent'];
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
        }
    }
};
</script>

<style lang="scss" scoped>
.fj-bar-chart {
    margin: 0;
}
</style>
