<template>
    <b-col :cols="cols">
        <fj-index-table
            :items="sessions"
            :cols="tableCols"
            :loadItems="loadSessions"
            no-select
        >
            <template slot="header">
                <b-row>
                    <fj-profile-security-change-password />
                </b-row>
                <h5>{{ __('profile.logged_in_devices').capitalizeAll() }}</h5>
            </template>
        </fj-index-table>
    </b-col>
</template>

<script>
export default {
    name: 'ProfileSecurity',
    props: {
        cols: {
            type: Number,
            required: true
        },
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
            let response = await axios.get(
                'profile/settings/sessions',
                payload
            );
            this.sessions = response.data.items;

            return response;
        }
    }
};
</script>
