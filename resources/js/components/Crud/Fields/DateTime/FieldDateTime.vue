<template>
    <lit-base-field :field="field" :model="model">
        <template v-if="!field.readonly && date">
            <v-date-picker
                :value="value"
                @input="handleInput"
                is24hr
                :mode="mode"
                :minute-increment="minuteIncrement"
                :locale="Lit.getLocale()"
                class="lit_date_time_picker"
            >
                <template v-slot="{ inputValue, inputEvents }">
                    <input
                        class="form-control lit-field-input"
                        :value="inputValue"
                        v-on="inputEvents"
                    />
                </template>
            </v-date-picker>
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
    data() {
        return {
            date: _.clone(this.value),
        };
    },
    watch: {
        date(val) {
            this.$emit('input', val);
        },
    },
    methods: {
        handleInput(event) {
            this.$emit('input', this.formatdDate(event));
        },
        formatdDate(date) {
            var d = new Date(date),
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
        mode() {
            if (this.field.only_time) {
                return 'time';
            }
            if (this.field.only_date) {
                return 'date';
            }
            return 'dateTime';
        },
        minuteIncrement() {
            if (this.field.minute_interval) {
                return this.field.minute_interval;
            }
            return 60;
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
.lit_date_time_picker {
    width: 100%;
    .vc-highlight {
        background: $primary !important;
    }
    .vc-weekday,
    .vc-month,
    .vc-day,
    .vc-year {
        color: $secondary !important;
    }
}
</style>
