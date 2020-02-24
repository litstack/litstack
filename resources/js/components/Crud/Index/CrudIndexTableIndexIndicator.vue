<template>
    <small class="text-primary fj-crud-index-table__index-indicator"
        >{{ page * (perPage || 1) - (perPage || 1) + 1 }} -
        {{
            page * (perPage || 1) > total
                ? total || crud.items.length
                : page * (perPage || 1)
        }}
        {{ $t('of') }} {{ total || crud.items.length }}</small
    >
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudIndexTableIndexIndicator',
    data() {
        return {
            page: 1,
            total: null
        };
    },
    computed: {
        ...mapGetters(['form', 'crud']),
        perPage() {
            return this.form.config.index.per_page || 0;
        }
    }
};
</script>

<style lang="css" scoped></style>
