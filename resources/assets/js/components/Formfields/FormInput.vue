<template>
    <fj-form-item :field="field">

        <input
            class="form-control"
            v-model="field.model"
            :placeholder="field.placeholder"
            @input="$emit('changed')"/>

        <fj-form-language :field="field"/>

        <slot />

    </fj-form-item>
</template>

<script>
export default {
    name: 'FormInput',
    props: {
        field: {
            required: true,
            type: Object
        },
    }
};
</script>

<style lang="css"></style>
