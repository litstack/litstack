<template>
    <span>
        <template v-if="item.payload && 'location' in item.payload">
            {{ item.payload.location.city }},
            {{ __country(item.payload.location.country) }}
        </template>
    </span>
</template>

<script>
export default {
    name: 'ProfileSecuritySessionLocation',
    props: {
        item: {
            required: true,
            type: Object
        }
    }
};
</script>
