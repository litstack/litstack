<template>
    <div class="fj-crud-index-table__form mb-2">
        <b-input-group>
            <b-input-group-prepend is-text>
                <fa-icon icon="search" />
            </b-input-group-prepend>

            <fj-crud-index-table-search />

            <template v-slot:append>
                <fj-crud-index-table-filter />
                <fj-crud-index-table-sort />
            </template>
        </b-input-group>
    </div>
</template>

<script>
export default {
    name: 'CrudIndexTableForm'
};
</script>
