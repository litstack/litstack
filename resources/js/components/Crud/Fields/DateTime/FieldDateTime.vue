<template>
    <lit-base-field :field="field" :model="model">
        <template v-if="!field.readonly">
            <div class="w-100">
                <v-date-picker
                    v-model="datetime"
                    :model-config="modelConfig"
                    :minute-increment="field.minute_interval"
                    :mode="field.mode"
                    :is24hr="field.is24hr"
                    :locale="Lit.getLocale()"
                    :is-expanded="field.expand"
                    :trim-weeks="field.trimWeeks"
                    :rows="field.rows"
                    :min-date="field.minDate"
                    :max-date="field.maxDate"
                    :attributes="attributes"
                    class="lit_date_time_picker"
                >
                    <template
                        v-slot="{ inputValue, inputEvents }"
                        v-if="!field.inline"
                    >
                        <input
                            class="form-control lit-field-input"
                            :value="inputValue"
                            v-on="inputEvents"
                        />
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
    name: 'FieldDateTime',
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
        datetime(val) {
            this.$emit('input', val);
        },
    },
    data() {
        return {
            datetime: null,
            modelConfig: {
                type: 'string',
                mask: null,
            },
        };
    },
    methods: {
        setInitialValue() {
            if (this.value) {
                this.datetime = this.value;
            } else if (this.field.mode == 'time') {
                this.datetime = 1;
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
    .vc-highlight {
        background: $primary !important;
    }
    .vc-date {
        display: none !important;
    }
    &.vc-container {
        border-color: $secondary !important;
    }
}
</style>
