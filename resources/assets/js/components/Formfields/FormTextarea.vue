<template>
    <fj-form-item :field="field">

        <textarea
            class="form-control"
            v-model="field.model"
            :id="field.id"
            :rows="field.rows"
            :placeholder="field.placeholder"
            @input="$emit('changed')">
        </textarea>

        <fj-form-language :field="field"/>

        <slot />

    </fj-form-item>
</template>

<script>
export default {
    name: 'FormTextarea',
    props: {
        field: {
            required: true,
            type: Object
        },
    }
};
</script>

<style lang="css"></style>
