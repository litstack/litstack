<template>
    <fj-base-container>
        <fj-base-header :title="'Fjord ' + $t('fj.users')">
            <div slot="actions-right">
                <fj-user-create @userCreated="userCreated" />
            </div>
        </fj-base-header>

        <b-row>
            <b-col>
                <fj-index-table
                    ref="indexTable"
                    :cols="config.index"
                    :items="users"
                    :load-items="loadUsers"
                    :name-singular="$t('fj.user')"
                    :name-plural="$t('fj.users')"
                    :per-page="config.perPage"
                    :sort-by="config.sortBy"
                    :sort-by-default="config.sortByDefault"
                    :filter="config.filter"
                    :global-actions="config.globalActions"
                    :record-actions="config.recordActions"
                />
            </b-col>
        </b-row>
    </fj-base-container>
</template>

<script>
export default {
    name: 'Users',
    props: {
        config: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            users: [],
            data: {}
        };
    },
    methods: {
        reload() {
            this.$refs.indexTable.$emit('reload');
        },
        async loadUsers(payload) {
            let response = await axios.post('fjord/users-index', payload);
            this.users = response.data.items;

            return response;
        },

        userCreated(user) {
            this.reload();
        }
    }
};
</script>
