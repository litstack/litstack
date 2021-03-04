<template>
    <lit-base-field
        :field="field"
        :model="model"
        :value="value"
        class="lit-field__range"
    >
        <b-input-group>
            <b-form-input
                ref="input"
                :value="value"
                @input="input"
                type="range"
                number
                :min="field.min"
                :max="field.max"
                :step="field.step"
                v-bind:disabled="field.readonly"
            />
            <div
                :style="
                    `width: ${((value - field.min) / (field.max - field.min)) *
                        100}%`
                "
                class="lit-range-progress"
            ></div>
        </b-input-group>

        <slot />
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldRange',
    props: {
        field: {
            required: true,
            type: Object,
        },
        model: {
            required: true,
            type: Object,
        },
        value: {
            required: true,
        },
    },
    beforeMount() {
        if (this.value === undefined) {
            this.$emit('input', this.field.min);
        }
    },
    mounted() {
        this.setWidth(this.value);
    },
    watch: {
        value(val) {
            this.setWidth(val);
        },
    },
    methods: {
        /**
         * Emit integer value.
         *
         * @param {String} newValue
         * @return {undefined}
         */
        input(newValue) {
            this.$emit('input', parseInt(newValue));
        },

        /**
         * Calculate percentage.
         *
         * @param {Number} value
         * @return {undefined}
         */
        percentage(value) {
            return (
                ((value - this.field.min) / (this.field.max - this.field.min)) *
                100
            );
        },

        /**
         * Set bar width.
         *
         * @return {undefined}
         */
        setWidth(value) {
            this.$refs.input.$el.style.background =
                'linear-gradient(to right, #70859c 0%, #70859c ' +
                this.percentage(value) +
                '%, #fff ' +
                this.percentage(value) +
                '%, white 100%)';
        },
    },
};
</script>

<style lang="scss" scoped>
@import '@lit-sass/_variables';
.lit-range-progress {
    position: absolute;
    height: 3px;
    left: 0;
    background: $primary;
    z-index: 3;
    pointer-events: none;
    border-radius: 9999px;
}
.lit-field__range {
    .input-group {
        height: $button-md-height / 1.5;
        align-items: center;
    }
    .custom-range {
        border: none;
        padding: 0;

        background: linear-gradient(
            to right,
            $secondary 0%,
            $secondary 50%,
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
