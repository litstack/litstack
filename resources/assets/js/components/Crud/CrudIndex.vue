<template>
    <div>
        <fj-table-index
            :items="items"
            :fields="fields"
            :actions="['edit', 'delete']"/>
    </div>
</template>

<script>
export default {
    name: 'CrudIndex',
    props: {
        models: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            fields: [
                {
                    key: 'title',
                    label: 'Title',
                    sortable: true
                },
            ],
            items: {},
        };
    },
    beforeMount() {
        this.items = this.models.items
    }
};
</script>
