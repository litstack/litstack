<template>
    <fj-form-item :field="field" :model="model" :value="value">
        <b-form-textarea
            :value="model[`${field.id}Model`]"
            :placeholder="field.placeholder"
            :rows="field.rows"
            :max-rows="field.max_rows"
            :maxlength="field.max"
            @input="changed"
        >
        </b-form-textarea>

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
        model: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            value: this.model[`${this.field.id}Model`]
        };
    },
    methods: {
        changed(value) {
            this.value = value;
            this.model[`${this.field.id}Model`] = value;
            this.$emit('changed');
        }
    }
};
</script>

<style lang="css"></style>
