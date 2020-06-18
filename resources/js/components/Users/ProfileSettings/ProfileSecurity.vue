<template>
    <fj-col :width="12">
        <fj-index-table
            :items="sessions"
            :cols="tableCols"
            :loadItems="loadSessions"
            name-singular="device"
            name-plural="devices"
            no-select
            no-card
        >
            <template slot="header">
                <h5>{{ __('profile.logged_in_devices').capitalizeAll() }}</h5>
            </template>
        </fj-index-table>
    </fj-col>
</template>

<script>
export default {
    name: 'ProfileSecurity',
    props: {
        model: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            sessions: [],
            tableCols: [
                {
                    component: 'fj-profile-security-session-device',
                    label: this.__('base.device').capitalize(),
                    small: true
                },
                {
                    component: 'fj-profile-security-session-current',
                    label: ''
                },
                {
                    component: 'fj-profile-security-session-location',
                    label: this.__('base.location').capitalize()
                },
                {
                    value: '{last_activity_readable}',
                    label: this.__('profile.last_activity').capitalizeAll()
                },
                {
                    component: 'fj-profile-security-session-logout',
                    label: '',
                    small: true
                }
            ]
        };
    },
    methods: {
        async loadSessions(payload) {
            let response = await axios.get('profile-sessions', payload);
            this.sessions = response.data.items;

            return response;
        }
    }
};
</script>
<style lang="scss">
@import '@fj-sass/_variables';
.profile-security .fj-index-table {
    margin-left: -$card-spacer-x;
    margin-right: -$card-spacer-x;
}
</style>
