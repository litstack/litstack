<template>
    <fj-col :width="width">
        <div class="fj-chart card" :class="this.variant">
            <div class="px-3 pt-3">
                <h4 class="fj-chart__title">
                    {{ title }}
                </h4>
            </div>
            <div class="fj-chart__wrapper">
                <apexchart
                    type="area"
                    height="400px"
                    :options="options"
                    :series="series"
                ></apexchart>
            </div>
            <div class="fj-chart__legend px-3 pb-1">
                <h3 class="mb-0">
                    3.253 $
                    <fa-icon icon="arrow-up" class="text-success" />
                </h3>
                <small>
                    daily goal 1 million bucks
                </small>
            </div>
        </div>
    </fj-col>
</template>

<script>
export default {
    name: 'Chart',
    props: {
        title: {
            type: String
        },
        variant: {
            type: String,
            default: 'white'
        },
        width: {
            type: Number,
            default: 4
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
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: false
                    }
                },
                xaxis: {
                    categories: [
                        1991,
                        1992,
                        1993,
                        1994,
                        1995,
                        1996,
                        1997,
                        1998
                    ],
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
                {
                    name: 'series-1',
                    data: [30, 40, 45, 150, 49, 60, 70, 50]
                },
                {
                    color: '',
                    name: 'series-2',
                    data: [120, 75, 100, 110, 120, 20, 30, 10]
                }
            ]
        };
    },
    methods: {
        // {
        //     labels: [],
        //     values: [{
        //         name: '',
        //         data: []
        //     }]
        // }

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
                ? this.secondary
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
    },
    beforeMount() {
        let color = this[this.variant];
        this.resetColors();
        this.setCardColor(color);
        console.log('FIRST');
        this.addColor(color);
        console.log('SECOND');
        this.addColor(color, true);
        //this.addColor(this.secondary);
    }
};
</script>

<style lang="scss" scoped>
@import '@fj-sass/_variables';
.fj-chart {
    &__title {
        font-weight: 700;
        font-size: 1.25rem;
        margin: 0;
        max-width: 70%;
    }
    &__wrapper {
        margin: 0 -12px;
        margin-top: -2rem;
    }
    &__legend {
        margin-top: -2rem;
    }

    &.primary {
        background: $primary;
        h3,
        h4 {
            color: white;
        }
    }
    &.secondary {
        background: $secondary;
        h3,
        h4 {
            color: white;
        }
    }
}
</style>
