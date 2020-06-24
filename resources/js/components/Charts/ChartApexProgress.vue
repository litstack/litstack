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
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '70%',
                            background: 'transparent',
                            position: 'back'
                        },
                        track: {
                            show: true,
                            background: '#f2f2f2',
                            strokeWidth: '10%'
                        },
                        dataLabels: {
                            name: {
                                fontSize: '22px'
                            },
                            value: {
                                fontSize: '16px'
                            },
                            total: {
                                show: true,
                                label: undefined,
                                formatter: function(val) {
                                    return val.globals.series[0];
                                }
                            }
                        }
                    }
                },
                fill: {
                    type: 'solid',
                    opacity: 1,
                    colors: []
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
            // this.options.fill = {
            //     colors: []
            // };
            this.options.fill.colors.push(firstColor(variant));
        },

        update(data) {
            this.$refs.chart.updateOptions({
                xaxis: { categories: data.categories }
            });
            this.series = data.series;
            console.log('series: ', data.series);

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
