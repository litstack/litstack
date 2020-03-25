<template>
    <b-dropdown-item @click="visible = true" :disabled="true">
        {{ $t('fj.user_profile') }}
        <b-modal v-model="visible" :title="$t('fj.user_profile')" size="lg">
            <!-- todo -->
        </b-modal>
    </b-dropdown-item>
</template>

<script>
export default {
    name: 'UserProfile',
    data() {
        return {
            visible: false
        };
    }
};
</script>
