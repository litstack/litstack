<template>
    <fj-base-container>
        <fj-base-header :title="'Fjord Users'">
            <div slot="actions-right">
                <fj-user-create @userCreated="userCreated" />
            </div>
        </fj-base-header>

        <b-row>
            <b-col>
                <fj-index-table
                    :cols="config.cols"
                    :items="users"
                    :count="count"
                    :loadItems="loadUsers"
                    :nameSingular="$t('fj.user')"
                    :namePlural="$t('fj.users')"
                    :sortBy="config.sortBy"
                    :sortByDefault="config.sortByDefault"
                    :filter="config.filter"
                    :globalActions="config.globalActions"/>
            </b-col>
        </b-row>
    </fj-base-container>
</template>

<script>
export default {
    name: 'Users',
    props: {
        usersCount: {
            type: Number,
            required: true
        },
        config: {

        }
    },
    data() {
        return {
            users: [],
            count: 0,
            data: {},
        };
    },
    beforeMount() {
        this.count = this.usersCount
    },
    methods: {
        async loadUsers(payload) {
            let response = await axios.post('users-index', payload)
            this.users = response.data.items
            this.count = response.data.count
        },

        userCreated(user) {
            // TODO: better table reload
            window.location.reload()
        }
    }
};
</script>
