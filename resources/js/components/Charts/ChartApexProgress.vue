<template>
    <apexchart
        class="lit-bar-chart"
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
    variantColor,
} from './chart.service';
export default {
    name: 'ChartApexProgress',
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
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '82%',
                            margin: 0,
                            background: 'transparent',
                            position: 'back',
                        },
                        track: {
                            show: false,
                            // background: '#f2f2f2',
                            // strokeWidth: '10%'
                        },
                        dataLabels: {
                            show: true,
                            value: {
                                show: true,
                                fontSize: '2rem',
                                fontFamily: 'Inter',
                                fontWeight: 700,
                                color: 'white',
                                offsetY: -4,
                            },
                            name: {
                                fontSize: '22px',
                            },
                            total: {
                                show: true,
                                label: undefined,
                                fontSize: '1rem',
                                fontFamily: 'Inter',
                                fontWeight: 400,
                                // formatter: function(val) {
                                //     return `${val.globals.series[0]}`;
                                // }
                            },
                        },
                    },
                },
                fill: {
                    type: 'solid',
                    opacity: 1,
                    colors: [],
                },
                labels: [],
                stroke: {},
            },

            series: [],
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
            this.options.plotOptions.radialBar.dataLabels.value.color = this.dataLabelColor(
                variant
            );
        },
        dataLabelColor(variant) {
            if (variant == PRIMARY || variant == SECONDARY) {
                return 'white';
            }
            return 'black';
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
    },
};
</script>

<style lang="scss">
.apexcharts-radialbar-hollow {
    // transform: scale(1.06);
    // transform-origin: 32%;
}

.lit-chart.primary,
.lit-chart.secondary {
    circle.apexcharts-radialbar-hollow {
        fill: url(#svg-gradient-white) !important;
    }
}
.lit-chart.white {
    circle.apexcharts-radialbar-hollow {
        fill: url(#svg-gradient-primary) !important;
    }
}
</style>
