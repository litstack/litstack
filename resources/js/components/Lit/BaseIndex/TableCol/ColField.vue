<template>
    <div class="d-flex align-items-center">
        <lit-field :model="item" :field="Lit.clone(field)" />
    </div>
</template>

<script>
export default {
    name: 'ColField',
    props: {
        item: {
            type: Object,
        },
        field: {
            type: Object,
        },
    },
};
</script>
