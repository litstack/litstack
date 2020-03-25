<template>
    <b-dropdown
        right
        :text="$t('fj.filter')"
        class="btn-br-none"
        :variant="filterVariant"
        :disabled="!form.config.index.filter"
    >
        <b-dropdown-group
            :header="key"
            v-for="(group, key) in form.config.index.filter"
            :key="key"
        >
            <b-dropdown-item-button
                v-for="(item, index) in group"
                @click="filter(index)"
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
    name: 'CrudIndexTableFilter',
    data() {
        return {
            filter_scope: ''
        };
    },
    methods: {
        filter(key) {
            this.filter_scope = key;
            this.$bus.$emit('crudFilter', key);
        },
        resetFilter() {
            this.filter_scope = null;
            this.$bus.$emit('crudFilter', null);
        },
        filterActive(key) {
            return key == this.filter_scope;
        }
    },
    computed: {
        ...mapGetters(['form']),
        filterVariant() {
            return this.filter_scope ? 'primary' : 'outline-secondary';
        }
    }
};
</script>
