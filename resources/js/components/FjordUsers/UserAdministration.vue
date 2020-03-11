<template>
    <b-dropdown-group :header="$t('user_admin')">
        <slot />
        <fj-user-profile />
        <b-dropdown-divider></b-dropdown-divider>
    </b-dropdown-group>
</template>

<script>
export default {
    name: 'UserAdministration'
};
</script>
