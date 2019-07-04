<template>
    <div class="pb-3">
        <h5 class="mb-0">
            <label :for="field.id">{{ field.title }}</label>
        </h5>
        <slot />
        <small class="form-text text-muted">{{ field.hint }}</small>
    </div>
</template>

<script>
export default {
    name: 'BaseFormitem',
    props: {
        field: {
            type: Object,
            required: true
        }
    }
};
</script>

<style lang="css" scoped></style>
