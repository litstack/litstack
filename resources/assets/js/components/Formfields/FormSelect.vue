<template>
    <fj-form-item :field="field">
        <select
            class="form-control"
            :value="this.field.model"
            @input="handle($event.target.value)">
            <option
                :value="`${key}`"
                v-for="(value, key) in options">
                {{ value }}
            </option>
        </select>
        <slot />
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormSelect',
    props: {
        field: {
            required: true,
            type: Object
        },
    },
    methods: {
        handle(val) {
            this.field.model = val
            this.$emit('changed')
        }
    },
    computed: {
        options() {
            return this.field.options
        }
    }
};
</script>

<style lang="css"></style>
