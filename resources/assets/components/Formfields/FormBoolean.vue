<template>
    <fj-form-item :field="field">

        <button
            @click.prevent="toggleValue"
            class="btn btn-lg">
            <i
                class="fas fa-toggle-on text-success"
                v-if="model[`${field.id}Model`]"/>
            <i
                class="fas fa-toggle-off text-danger"
                v-else/>
        </button>

        <slot />

    </fj-form-item>
</template>

<script>
export default {
    name: 'FormBoolean',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
        }
    },
    methods: {
        toggleValue() {
            this.model[`${this.field.id}Model`] = !this.model[`${this.field.id}Model`]
            this.$emit('changed')
        }
    }
};
</script>

<style lang="css"></style>
