<template>
    <lit-base-field :field="field" :model="model">
        <template v-if="!field.readonly">
            <v-select
                :value="value"
                :options="options"
                :label="isArray ? null : 'value'"
                :reduce="isArray ? item => item : item => item.key"
                :placeholder="field.placeholder"
                class="w-100"
                v-on:input="$emit('input', $event)"
            >
                <template #open-indicator="{ attributes }">
                    <span v-bind="attributes">
                        <lit-fa-icon icon="chevron-down" />
                    </span>
                </template>
            </v-select>
        </template>

        <template v-else>
            <b-input
                class="form-control"
                :value="reduceByValue(value)"
                type="text"
                readonly
            />
        </template>
        <slot />
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldSelect',
    props: {
        /**
         * Field attributes.
         */
        field: {
            required: true,
            type: Object,
        },

        /**
         * Model.
         */
        model: {
            required: true,
            type: Object,
        },

        /**
         * Field value.
         */
        value: {
            required: true,
        },
    },
    beforeMount() {
        if (this.value) {
            this.$emit('input', String(this.value));
        }
    },
    methods: {
        reduceByValue(value) {
            for (let i = 0; i < this.options.length; i++) {
                let option = this.options[i];
                if (option.key == value) {
                    return option.value;
                }
            }
        },
    },
    computed: {
        isArray() {
            return Array.isArray(this.field.options);
        },

        options() {
            if (this.isArray) {
                return this.field.options;
            }

            let array = _.map(this.field.options, (value, key) => {
                return {
                    key,
                    value,
                };
            });

            return array;
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
@import '~vue-select/src/scss/vue-select.scss';

.v-select {
    border: 1px solid $secondary;
    border-radius: $border-radius-md;
    box-shadow: none !important;
    padding: 0 0.5rem;
    height: 40px;
    background: white;
    &:hover {
        border-color: $secondary;
    }
    &:focus {
        background: $secondary;
        border-color: $secondary;
        color: white;
        box-shadow: none;
    }

    .vs__dropdown-toggle,
    .vs__dropdown-menu {
        background: transparent;
        border: none;
        color: white !important;
        border-radius: 0;
        padding: 0 !important;
    }

    .vs__dropdown-menu {
        background: white;
        overflow-x: hidden;
        border: 1px solid $secondary;
        border-top: none;
        border-bottom-left-radius: $border-radius-md;
        border-bottom-right-radius: $border-radius-md;
        box-shadow: 0px 10px 10px 0px rgba(0, 0, 0, 0.05);
        box-sizing: content-box !important;
        transform: translateX(-1px);
    }

    .vs__search,
    .vs__selected {
        margin: 0;
        padding: 0;
        font-size: 0.9rem;
        font-weight: 400;
        color: black;
        height: 38px;
        &::placeholder {
            color: $gray-600;
        }
    }

    .vs__selected-options {
        flex-wrap: unset;
    }

    &.vs--open {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
        .vs__search {
            background-color: transparent;
        }
        .vs__open-indicator {
            margin-top: -4px;
        }
    }

    .vs__dropdown-option {
        position: relative;
        padding: 0.5rem 0.875rem;
        font-size: 0.9rem;
        color: black;
        transition: all 0.3s;

        &:hover {
            cursor: pointer;
            background-color: $primary;
            color: white;
        }
    }

    .vs__dropdown-option--highlight {
        background-color: $primary;
        color: white;
    }

    .vs__clear {
        display: none;
    }

    .vs__open-indicator {
        margin-top: 0;
        transition: 0.2s all ease;
        color: $secondary;
    }

    &.vs--open {
        .vs__actions {
            &::after {
                transform: rotate(180deg);
            }
        }
    }
}
</style>
