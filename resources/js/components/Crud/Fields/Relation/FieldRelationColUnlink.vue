<template>
    <fa-icon
        icon="unlink"
        class="text-secondary fj-field-relation-unlink"
        @click="$emit('unlink', item)"
    />
</template>

<script>
export default {
    name: 'FieldRelationColUnlink',
    props: {
        item: {
            type: Object,
            required: true
        }
    }
};
</script>

<style>
.fj-field-relation-unlink {
    cursor: pointer;
}
</style>
