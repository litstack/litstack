<template>
    <b-form-input
        :placeholder="
            $t('fj.search_models', {
                models: names.title.plural
            })
        "
        v-model="search"
    />
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudIndexTableSearch',
    data() {
        return {
            search: ''
        };
    },
    watch: {
        search(val) {
            this.$bus.$emit('crudSearch', val);
        }
    },
    computed: {
        ...mapGetters(['form']),
        names() {
            return this.form.config.names;
        }
    }
};
</script>
