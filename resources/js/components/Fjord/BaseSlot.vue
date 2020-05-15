<template>
    <component
        :is="name"
        v-bind="{ ...props, ...$attrs }"
        :slots="slots"
        v-on="$listeners"
    />
</template>

<script>
export default {
    name: 'Slot',
    props: {
        props: {
            type: [Object, Array],
            default() {
                return {};
            }
        },
        slots: {
            type: [Object, Array],
            default() {
                return {};
            }
        },
        name: {
            type: String,
            required: true
        }
    }
};
</script>
