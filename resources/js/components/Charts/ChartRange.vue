<template>
    <b-dropdown
        :text="active.title"
        :variant="'primary'"
        class="mt-auto mb-2"
        right
    >
        <template v-slot:button-content>
            <lit-fa-icon icon="chart-line" /> {{ active.title }}
        </template>
        <b-dropdown-item
            v-for="(option, key) in options"
            :key="key"
            @click="active = option"
            v-bind:active="active == option"
        >
            {{ option.title }}
        </b-dropdown-item>
    </b-dropdown>
</template>

<script>
export default {
    name: 'ChartRange',
    props: {
        // default: {
        //     type: [Object, Array]
        // },
    },
    data() {
        return {
            active: {
                title: 'Last 7 days',
                key: 'last7days'
            },
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
            ]
        };
    },
    watch: {
        active(val) {
            Lit.bus.$emit('chartRangeChanged', val);
        }
    }
};
</script>
