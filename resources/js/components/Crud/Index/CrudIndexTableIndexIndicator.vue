<template>
    <div>
        <small class="text-primary fj-crud-index-table__index-indicator"
            >{{ crud.page * (perPage || 1) - (perPage || 1) + 1 }} -
            {{
                crud.page * (perPage || 1) > crud.total
                    ? crud.total || crud.items.length
                    : crud.page * (perPage || 1)
            }}
            {{ $t('of') }} {{ crud.total || crud.items.length }}</small
        >
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudIndexTableIndexIndicator',
    data() {
        return {
            page: 1
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
