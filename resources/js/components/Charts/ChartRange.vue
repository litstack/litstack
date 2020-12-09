<template>
    <b-dropdown
        :text="active.title"
        variant="outline-secondary"
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
                key: 'last7days',
            },
            options: [
                {
                    title: 'Last 24 Hours',
                    key: 'last24hours',
                },
                {
                    title: 'Today',
                    key: 'today',
                },
                {
                    title: 'Yesterday',
                    key: 'yesterday',
                },
                {
                    title: 'Last 7 days',
                    key: 'last7days',
                },
                {
                    title: 'This Week',
                    key: 'thisweek',
                },
                {
                    title: 'Last 30 days',
                    key: 'last30days',
                },
                {
                    title: 'This Month',
                    key: 'thismonth',
                },
                {
                    title: 'This Year',
                    key: 'thisyear',
                },
            ],
        };
    },
    watch: {
        active(val) {
            Lit.bus.$emit('chartRangeChanged', val);
        },
    },
};
</script>
