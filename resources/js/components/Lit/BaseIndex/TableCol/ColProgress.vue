<template>
    <b-progress v-bind="$attrs" :value="value" height=".5rem" />
</template>

<script>
export default {
    name: 'ColProgress',
    props: {
        value: {
            required: true,
        },
    },
};
</script>
