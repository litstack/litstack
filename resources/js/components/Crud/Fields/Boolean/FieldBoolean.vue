<template>
    <fj-base-field :field="field" :model="model">
        <b-form-checkbox
            v-model="value"
            class="fj-form-item-boolean"
            name="check-button"
            switch
            @change="$emit('input', $event)"
        />
    </fj-base-field>
</template>

<script>
export default {
    name: 'FieldBoolean',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true
        },
        value: {
            required: true
        }
    }
};
</script>

<style lang="scss" scoped>
@import '@fj-sass/_variables';
.fj-form-item-boolean {
    height: $button-md-height / 1.5;
    align-items: center;
}
</style>
