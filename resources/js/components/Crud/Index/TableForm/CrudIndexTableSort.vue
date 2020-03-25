<template>
    <b-dropdown
        right
        :text="$t('fj.sort')"
        class="btn-brl-none"
        variant="outline-secondary"
        v-if="form.config.index.sort_by"
    >
        <b-dropdown-item
            v-for="(text, key) in form.config.index.sort_by"
            :key="key"
            @click="sortBy(key)"
        >
            <b-form-radio :checked="sort_by_key" :value="key">{{
                text
            }}</b-form-radio>
        </b-dropdown-item>
    </b-dropdown>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudIndexTableSort',
    data() {
        return {
            sort_by_key: ''
        };
    },
    methods: {
        sortBy(key) {
            this.sort_by_key = key;
            this.$bus.$emit('crudSort', key);
        }
    },
    computed: {
        ...mapGetters(['form'])
    }
};
</script>
