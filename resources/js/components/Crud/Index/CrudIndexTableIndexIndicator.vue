<template>
    <div>
        <small class="text-primary fj-crud-index-table__index-indicator"
            >{{ from }} - {{ to }} {{ $t('of') }} {{ total }}</small
        >
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudIndexTableIndexIndicator',
    computed: {
        ...mapGetters(['form', 'crud']),
        perPage() {
            return this.form.config.index.per_page || 0;
        },
        from() {
            return (
                this.crud.page * (this.perPage || 1) -
                    (this.perPage || 1) +
                    1 || 1
            );
        },
        to() {
            return (
                (this.crud.page * (this.perPage || 1) > this.crud.total
                    ? this.crud.total || this.crud.items.length
                    : this.crud.page * (this.perPage || 1)) || this.total
            );
        },
        total() {
            return this.crud.total || this.crud.items.length;
        }
    }
};
</script>

<style lang="css" scoped></style>
