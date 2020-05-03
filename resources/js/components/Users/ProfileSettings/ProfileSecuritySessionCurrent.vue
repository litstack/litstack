<template>
    <span>
        <b-badge variant="primary" v-if="item.is_current">
            {{ __('profile.current_session').capitalizeAll() }}
        </b-badge>
    </span>
</template>

<script>
export default {
    name: 'ProfileSecuritySessionCurrent',
    props: {
        item: {
            required: true,
            type: Object
        }
    }
};
</script>
