<template>
    <fj-form-item :field="field">

        <button
            @click.prevent="$emit('input', !value)"
            class="btn btn-lg">
            <i
                class="fas fa-toggle-on text-success"
                v-if="value"/>
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
        value: {
            required: true,
        }
    },
};
</script>

<style lang="css"></style>
