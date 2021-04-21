<template>
    <lit-base-field :field="field" :model="model">
        <template v-if="!field.readonly">
            <div class="w-100">
                <v-date-picker
                    v-model="range"
                    :mode="field.mode"
                    :model-config="modelConfig"
                    :is24hr="field.is24hr"
                    class="lit_date_time_picker"
                    is-range
                >
                    <template
                        v-slot="{ inputValue, inputEvents }"
                        v-if="!field.inline"
                    >
                        <div class="d-flex align-items-center">
                            <input
                                class="form-control lit-field-input"
                                :value="inputValue.start"
                                v-on="inputEvents.start"
                            />
                            <div class="text-center mx-2">
                                <lit-fa-icon icon="arrow-right" class="text-secondary"/>
                            </div>
                            <input
                                class="form-control lit-field-input"
                                :value="inputValue.end"
                                v-on="inputEvents.end"
                            />
                        </div>
                    </template>
                </v-date-picker>
            </div>
        </template>
        <template v-else>
            <b-input class="form-control" :value="value" type="text" readonly />
        </template>
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldDateRange',
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
        this.setInitialValue();

        this.modelConfig.mask = this.field.mask || 'YYYY-MM-DD HH:mm:ss';
    },
    watch: {
        range(val) {
            this.$emit('input', {
                [this.field.attributes.start]: val.start,
                [this.field.attributes.end]: val.end,
            });
        },
    },
    data() {
        return {
            range: {
                start: null,
                end: null,
            },
            modelConfig: {
                type: 'string',
                mask: null,
            },
        };
    },
    methods: {
        setInitialValue() {
            if (!this.value) {
                return;
            }

            if(this.field.attributes.start in this.value) {
                this.range.start = this.value[this.field.attributes.start];
            }

            if(this.field.attributes.end in this.value) {
                this.range.end = this.value[this.field.attributes.end];
            }
        },
    },
    computed: {
        attributes() {
            if (!this.field.events) {
                return;
            }

            return [
                ...this.field.events.map(event => ({
                    dates: event.date,
                    dot: {
                        color: event.color,
                        class: event.class,
                    },
                    popover: {
                        label: event.label,
                        labelClass: 'w-100',
                        visibility: 'focus',
                    },
                    customData: event,
                })),
            ];
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
// #a0a5f8
// #c8d6fb
.lit_date_time_picker {
    * {
        font-family: 'Inter';
    }
    .vc-weekday,
    .vc-month,
    .vc-day,
    .vc-year {
        color: $secondary !important;
    }
    .vc-highlights + span{
        color: white !important;
    }
    .vc-highlight {
        background: $primary !important;
        * {
           color: white !important;
        }
    }
    .vc-date {
        display: none !important;
    }
    &.vc-container {
        border-color: $secondary !important;
    }
}

</style>
