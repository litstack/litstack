<template>
    <fj-form-item :field="field" :model="model">
        <template v-if="!field.readonly">
            <b-select
                :value="value"
                :options="field.options"
                v-bind:readonly="field.readonly"
                @input="changed"
            />
        </template>
        <template v-else>
            <b-input class="form-control" :value="value" type="text" readonly />
        </template>
        <slot />
    </fj-form-item>
</template>

<script>
import methods from '../methods';

export default {
    name: 'FieldSelect',
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
            value: false,
            original: false
        };
    },
    beforeMount() {
        this.init();
    },
    methods: {
        ...methods,
        changed(value) {
            this.setValue(value);
            this.$emit('changed', value);
            Fjord.bus.$emit('fieldChanged');
        }
    }
};
</script>
