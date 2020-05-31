<template>
    <fj-form-item
        :field="field"
        :model="model"
        :value="value"
        v-slot:default="{ state }"
        v-on="$listeners"
    >
        <b-input-group :size="field.size">
            <b-input-group-prepend is-text v-if="field.prepend">
                <span v-html="field.prepend"></span>
            </b-input-group-prepend>
            <!--<fj-input ref="input" slot-scope="{ value }">-->
            <b-input
                class="form-control fj-field-input"
                :value="value"
                :placeholder="field.placeholder"
                :type="field.type"
                :maxlength="field.max"
                :required="field.required"
                :state="state"
                v-bind:readonly="field.readonly"
                @input="changed"
            />
            <!--</fj-input>-->
            <b-input-group-append is-text v-if="field.append">
                <span v-html="field.append"></span>
            </b-input-group-append>
        </b-input-group>

        <slot />
    </fj-form-item>
</template>

<script>
import methods from '../methods';

export default {
    name: 'FieldInput',
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
            value: '',
            original: ''
        };
    },
    beforeMount() {
        this.init();
    },
    methods: {
        ...methods,
        changed(val) {
            this.setValue(val);
            //this.$refs.input.$emit('changed', val);
            this.$emit('changed', value);
        }
    }
};
</script>
