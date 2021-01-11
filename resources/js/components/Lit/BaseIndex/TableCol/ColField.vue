<template>
    <div class="d-flex align-items-center">
        <lit-field :model="item" :field="Lit.clone(colField)" />
    </div>
</template>

<script>
export default {
    name: 'ColField',
    props: {
        item: {
            type: Object,
        },
        colField: {
            type: Object,
        },
    },
};
</script>
