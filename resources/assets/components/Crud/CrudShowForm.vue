<template>
    <b-col cols="12">
        <b-card v-for="(ids, key) in formConfig.layout" :key="key" class="mb-5">
            <fj-form :ids="ids" :model="model" />
        </b-card>
    </b-col>
</template>

<script>
export default {
    name: 'CrudShowForm',
    props: {
        formConfig: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        }
    }
};
</script>
