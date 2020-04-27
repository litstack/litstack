<template>
    <b-dropdown
        right
        :text="$t('fj.filter')"
        class="btn-brl-none"
        :variant="filterVariant"
        :disabled="!filter"
    >
        <b-dropdown-group
            :header="key"
            v-for="(group, key) in filter"
            :key="key"
        >
            <b-dropdown-item-button
                v-for="(item, index) in group"
                @click="doFilter(index)"
                :key="item"
                :active="filterActive(index)"
            >
                {{ item }}
            </b-dropdown-item-button>
        </b-dropdown-group>
        <b-dropdown-divider></b-dropdown-divider>
        <b-dropdown-item-button @click="resetFilter">
            reset
        </b-dropdown-item-button>
    </b-dropdown>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'BaseIndexTableFilter',
    props: {
        filter: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            filter_scope: ''
        };
    },
    methods: {
        doFilter(key) {
            this.filter_scope = key;
            this.$emit('onFilterChange', key);
        },
        resetFilter() {
            this.filter_scope = null;
            this.$emit('onFilterChange', null);
        },
        filterActive(key) {
            return key == this.filter_scope;
        }
    },
    computed: {
        filterVariant() {
            return this.filter_scope ? 'primary' : 'outline-secondary';
        }
    }
};
</script>
