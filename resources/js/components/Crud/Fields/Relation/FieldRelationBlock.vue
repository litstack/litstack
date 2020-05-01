<template></template>

<script>
export default {
    name: 'FieldRelationBlock',
    props: {
        block: {
            required: true,
            type: Object
        },
        field: {
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
