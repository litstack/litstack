<template>
    <lit-base-field :field="field" :model="model">
        <template v-if="!field.readonly">
            <div @mouseenter="handleMouseEnter" class="w-100">
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
        if (this.value) this.datetime = this.value;

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
        // cheat for buggy impossible null value
        handleMouseEnter() {
            if (!this.datetime) {
                this.datetime = this.now();
            }
        },
        now() {
            var d = new Date(),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear(),
                hours = '' + d.getHours(),
                minutes = '' + d.getMinutes(),
                seconds = '' + d.getSeconds();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            if (hours.length < 2) hours = '0' + hours;
            if (minutes.length < 2) minutes = '0' + minutes;
            if (seconds.length < 2) seconds = '0' + seconds;

            return `${[year, month, day].join('-')} ${[
                hours,
                minutes,
                seconds,
            ].join(':')}`;
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
