<template>
    <fj-col :width="chart.width" class="mb-4">
        <div class="fj-chart card" :class="chart.variant">
            <div
                class="fj-chart__title px-3 pt-3 d-flex justify-content-between"
            >
                <h4>
                    {{ chart.title }}
                </h4>
                <b-dropdown :text="active.title" size="sm" :variant="reverse">
                    <b-dropdown-item
                        v-for="(option, key) in options"
                        :key="key"
                        @click="active = option"
                        v-bind:active="active == option"
                    >
                        {{ option.title }}
                    </b-dropdown-item>
                </b-dropdown>
            </div>
            <div
                class="fj-chart__wrapper d-flex justify-content-around"
                v-if="busy"
                :style="`height: ${height}`"
            >
                <fj-spinner :variant="reverse" />
            </div>
            <div
                class="fj-chart__wrapper"
                :style="
                    `display: ${busy ? 'none' : 'block'};height: ${height};`
                "
            >
                <component
                    ref="chart"
                    :is="component"
                    :variant="chart.variant"
                    :height="height"
                    :type="chart.type"
                    :format="format"
                />
            </div>
            <div class="fj-chart__legend px-3 pb-1">
                <template v-if="chart.type == 'area'">
                    <h3 class="mb-0">
                        {{ result }}

                        <template v-if="trend">
                            <fa-icon :icon="trendIcon" :class="trendText" />
                        </template>
                    </h3>
                    <template v-if="trend">
                        <small :class="trendText">
                            {{ `${trend == 'up' ? '+' : ''}${difference}` }} ({{
                                `${trend == 'up' ? '+' : ''}${percentage}`
                            }}%)
                        </small>
                    </template>
                </template>
                <div v-else class="mt-5"></div>
            </div>
        </div>
    </fj-col>
</template>

<script>
export default {
    name: 'Chart',
    props: {
        chart: {},
        component: {
            type: String
        }
    },
    data() {
        return {
            height: '200px',
            busy: true,
            result: 0,
            trendUp: false,
            difference: 0,
            trend: null,
            percentage: 0,
            options: [
                {
                    title: 'Today',
                    key: 'today'
                },
                {
                    title: 'Yesterday',
                    key: 'yesterday'
                },
                {
                    title: 'Last 7 days',
                    key: 'last7days'
                },
                {
                    title: 'This Week',
                    key: 'thisweek'
                },
                {
                    title: 'Last 30 days',
                    key: 'last30days'
                },
                {
                    title: 'This Month',
                    key: 'thismonth'
                }
            ],
            active: {
                title: 'Last 7 days',
                key: 'last7days'
            }
        };
    },
    computed: {
        reverse() {
            switch (this.chart.variant) {
                case 'white':
                    return 'primary';
                    break;
                case 'primary':
                    return 'light';
                    break;
                case 'secondary':
                    return 'light';
                    break;
                default:
                    return 'primary';
                    break;
            }
        },
        trendText() {
            if (this.trend == 'up') return 'text-success';
            if (this.trend == 'down') return 'text-danger';
            if (this.trend == 'same') return '';
        },
        trendIcon() {
            if (this.trend == 'up') return 'arrow-up';
            if (this.trend == 'down') return 'arrow-down';
            if (this.trend == 'same') return 'arrow-left';
        }
    },
    beforeMount() {
        this.setHeight();
    },
    async mounted() {
        this.busy = true;
        await this.loadData();
        this.busy = false;
    },
    watch: {
        active() {
            this.loadData();
        }
    },
    methods: {
        format(value) {
            if (this.chart.format) {
                value = numeral(value).format(this.chart.format);
            }
            if (this.chart.prefix) {
                value = this.chart.prefix + value;
            }
            if (this.chart.suffix) {
                value += this.chart.suffix;
            }

            return value;
        },
        setHeight() {
            if (this.chart.height) {
                return (this.height = this.chart.height);
            }
            let cols = this.bCols(this.chart.width);
            if (cols > 9) {
                return (this.height = '400px');
            }
            if (cols > 6) {
                return (this.height = '300px');
            }
            if (cols > 3) {
                return (this.height = '250px');
            }
            if (cols > 0) {
                return (this.height = '200px');
            }
        },
        async loadData() {
            let response = await this.sendLoadData();
            if (!response) {
                return;
            }
            this.$refs.chart.update(response.data.chart);
            this.makeResults(response.data.results);
        },

        sendLoadData() {
            try {
                return axios.post('chart-data', {
                    key: this.chart.name,
                    type: this.active.key
                });
            } catch (e) {
                console.log(e);
            }
        },

        makeResults(results) {
            this.result = results[0];
            if ((results.length = 2)) {
                this.difference = (this.result - results[1]).toFixed(1);
                this.trend =
                    this.difference == 0
                        ? 'same'
                        : this.difference > 0
                        ? 'up'
                        : 'down';
                if (this.difference == 0) {
                    this.percentage = 0;
                } else {
                    this.percentage = (
                        (this.difference / this.result) *
                        100
                    ).toFixed(1);
                }
            } else {
                this.trend = null;
            }

            this.result = this.format(this.result);
        }
    }
};
</script>

<style lang="scss" scoped>
@import '@fj-sass/_variables';
.fj-chart {
    &__title {
        h4 {
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
            max-width: 70%;
        }
        z-index: 2;
    }

    &__wrapper {
        margin: 0 -10px 0 -12px;
        z-index: 1;
    }
    &__legend {
        margin-top: -1rem;
        z-index: 2;
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
        h4,
        .fj-chart__legend {
            color: white;
        }
    }
}
</style>
