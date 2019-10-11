<template>
    <fj-form-item :field="field" :model="model" :value="value">
        <b-input-group :size="field.size">
            <b-input-group-prepend is-text v-if="field.prepend">
                <span v-html="field.prepend"></span>
            </b-input-group-prepend>
            <b-input
                class="form-control"
                :value="model[`${field.id}Model`]"
                :placeholder="field.placeholder"
                :type="field.input_type"
                :maxlength="field.max"
                :required="field.required"
                @input="changed"
            />
            <b-input-group-append is-text v-if="field.append">
                <span v-html="field.append"></span>
            </b-input-group-append>
        </b-input-group>

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
