<template>
    <div>
        <base-index
            :parameters="config"
            :tableData="data"
            :columns="columns"
            :options="options"
        />
    </div>
</template>

<script>
export default {
    name: 'CrudIndex',
    props: {
        data: {
            type: Array,
            required: true
        },
        config: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            columns: ['title', 'edit'],
            options: {
                headings: {
                    title: 'Title',
                    edit: ''
                }
            }
        };
    }
};
</script>
