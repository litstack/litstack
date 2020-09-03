<template>
    <b-dropdown
        right
        :variant="filterVariant"
        :disabled="!filter"
        class="dropdown-md-square-mobile"
        no-caret
    >
        <template v-slot:button-content>
            <lit-fa-icon icon="filter" />
            <span class="d-none d-lg-inline-block">{{ __('lit.filter') }}</span>
        </template>

        <b-dropdown-group
            :header="key"
            v-for="(group, key) in filter"
            :key="key"
        >
            <b-dropdown-item-button
                v-for="(item, index) in group"
                @click="toggleFilter(index)"
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
            type: [Object, Array]
        },
        filterScopes: {
            type: Array,
            default() {
                return [];
            }
        }
    },
    data() {
        return {
            filter_scope: ''
        };
    },
    methods: {
        toggleFilter(filter) {
            if (this.filterActive(filter)) {
                this.$emit('removeFilter', filter);
            } else {
                this.$emit('addFilter', filter);
            }
        },
        resetFilter() {
            this.$emit('resetFilter', null);
        },
        filterActive(filter) {
            return this.filterScopes.includes(filter);
        }
    },
    computed: {
        filterVariant() {
            return this.filterScopes.length > 0
                ? 'primary'
                : 'outline-secondary';
        }
    }
};
</script>
