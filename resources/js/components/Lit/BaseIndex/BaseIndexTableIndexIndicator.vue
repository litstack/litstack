<template>
    <div>
        <small class="text-primary lit-crud-index-table__index-indicator">
            <template v-if="total">
                {{ from }} - {{ to }} {{ __('crud.of') }} {{ total }}
            </template>
        </small>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'BaseIndexTableIndexIndicator',
    props: {
        perPage: {
            required: true,
            type: Number,
        },
        currentPage: {
            required: true,
            type: Number,
        },
        total: {
            required: true,
            type: Number,
        },
        items: {
            required: true,
            type: Array,
        },
    },
    computed: {
        from() {
            return (
                this.currentPage * (this.perPage || 1) -
                    (this.perPage || 1) +
                    1 || 1
            );
        },
        to() {
            return this.from + this.items.length - 1;
        },
    },
};
</script>

<style lang="scss" scoped>
.lit-crud-index-table__index-indicator {
    display: block;
    margin-top: -5px;
}
</style>
