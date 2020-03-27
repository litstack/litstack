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
                    :cols="tableCols"
                    :items="users"
                    :count="count"
                    :loadItems="loadUsers"
                    :nameSingular="$t('fj.user')"
                    :namePlural="$t('fj.users')"
                    :recordActions="recordActions"/>
            </b-col>
        </b-row>
        <!--
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">User</th>
                            <th
                                scope="col"
                                v-for="(role, index) in roles"
                                :key="index"
                            >
                                {{ role.name.capitalize() }}
                            </th>
                        </tr>
                    </thead>
                    <tr v-for="(user, id) in items" :key="id">
                        <td scope="row">
                            <strong>{{ user.name }}</strong> ({{ user.email }})
                        </td>
                        <td v-for="(role, index) in roles" :key="index">
                            <b-form-checkbox
                                v-model="data[user.name][role.name]"
                                @change="toggleRole(role, user)"
                                name="check-button"
                                switch
                            >
                            </b-form-checkbox>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        -->
    </fj-base-container>
</template>

<script>
export default {
    name: 'FjordUsers',
    props: {
        recordActions: {
            type: Array
        },
        usersCount: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            users: [],
            count: 0,
            data: {},
            test: null,
            tableCols: [
                {
                    'key': '{name}',
                    'label': 'Name'
                },
                {
                    'key': '{email}',
                    'label': 'E-Mail'
                }
            ]
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

        }
    }
};
</script>
