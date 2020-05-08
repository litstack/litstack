<template>
    <fj-form-item :field="field" :model="model" :value="value">
        <b-input-group>
            <b-form-input
                ref="input"
                :value="value"
                @input="changed"
                type="range"
                number
                :min="field.min"
                :max="field.max"
                :step="field.step"
                v-bind:disabled="field.readonly"
            />
        </b-input-group>

        <slot />
    </fj-form-item>
</template>

<script>
import methods from '../methods';

export default {
    name: 'FieldRange',
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
            value: 0,
            original: 0
        };
    },
    beforeMount() {
        this.init();

        if (this.value === undefined) {
            this.value = this.field.min;
        }
    },
    mounted() {
        this.setWidth(this.value);
    },
    methods: {
        ...methods,
        percentage(value) {
            return (
                ((value - this.field.min) / (this.field.max - this.field.min)) *
                100
            );
        },
        setWidth(value) {
            this.$refs.input.$el.style.background =
                'linear-gradient(to right, #4951f2 0%, #4951f2 ' +
                this.percentage(value) +
                '%, #fff ' +
                this.percentage(value) +
                '%, white 100%)';
        },
        changed(value) {
            this.setValue(value);
            this.$emit('changed', value);

            this.setWidth(value);
        }
    }
};
</script>

<style lang="scss" scoped>
@import '@fj-sass/_variables';
.fj-form-item-range {
    .input-group {
        height: $button-md-height / 1.5;
        align-items: center;
    }
    .custom-range {
        border: none;
        padding: 0;

        background: linear-gradient(
            to right,
            $primary 0%,
            $primary 50%,
            #fff 50%,
            #fff 100%
        );
        height: 3px;
        transition: background 450ms ease-in;
        -webkit-appearance: none;

        &::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 13px;
            width: 13px;
            border-radius: 13px;
            background: $primary;
            cursor: pointer;
            margin-top: -6px; /* You need to specify a margin in Chrome, but in Firefox and IE it is automatic */
            box-shadow: $button-primary-shadow;
            &:before {
                content: '';
                width: 10px;
                heigth: 10px;
                display: block;
                background: red;
            }
        }

        &::-webkit-slider-runnable-track {
            width: 100%;
            height: 1px;
            cursor: pointer;
            background: $input-border-color;
        }
    }
}
</style>
