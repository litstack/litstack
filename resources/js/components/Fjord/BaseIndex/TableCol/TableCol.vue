<template>
    <div>
        <div
            v-if="'values' in col"
            v-html="getColValue(col, item[col.value])"
        />
        <div v-else v-html="_format(col.value, item)" />
    </div>
</template>

<script>
export default {
    name: 'TableCol',
    props: {
        item: {
            required: true,
            type: Object
        },
        col: {
            required: true,
            type: Object
        }
    },
    methods: {
        getColValue(col, value) {
            let checkValue = value;
            checkValue = checkValue === true ? '1' : checkValue;
            checkValue = checkValue === false ? '0' : checkValue;

            if (checkValue in col.values) {
                return col.values[checkValue];
            }

            if ('default' in col.values) {
                return col.values.default;
            }

            return value;
        }
    }
};
</script>
