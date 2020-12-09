<template>
    <b-tag
        style="height: 28px;"
        variant="primary"
        @remove="$emit('remove', filter)"
    >
        <template v-if="typeof filter == 'string'">
            {{ filter }}
        </template>
        <template v-else>
            <template v-for="(value, attribute) in filter.values">
                {{ formatted(value, attribute) }}
            </template>
        </template>
    </b-tag>
</template>
<script>
export default {
    name: 'BaseIndexFilterTag',
    props: {
        filter: {
            type: [Object, String],
        },
    },
    methods: {
        /**
         * Determine if value is last.
         */
        isLast(value) {
            let last = _.last(Object.values(this.filter.values));

            console.log(last, value);

            return value == last;
        },
        /**
         * Get formatted tag value for the given attribute.
         */
        formatted(value, attribute) {
            let formatted = this.label(attribute) + this.value(value);

            if (!this.isLast(value)) {
                formatted += ',';
            }

            return formatted;
        },
        /**
         * Get label for the give attribute
         */
        label(attribute) {
            return this.filter.attributeNames[attribute];
        },
        /**
         * Get the formatted value.
         */
        value(raw) {
            let value = '';

            if (typeof raw == 'boolean') {
                return value;
            }

            value += ': ';

            if (typeof raw == 'object') {
                return (value += raw.join(', '));
            }

            if (this.isDate(raw)) {
                value += raw.split(' ')[0];
            } else {
                value += raw;
            }

            return value;
        },
        /**
         * Determine if the given string is a date.
         */
        isDate(date) {
            return new Date(date) !== 'Invalid Date' && !isNaN(new Date(date));
        },
    },
};
</script>
