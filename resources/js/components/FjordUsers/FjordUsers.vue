<template>
    <fj-base-container>
        <fj-base-header :title="'Fjord Users'">
            <div slot="actions-right">
                <fj-user-create @userCreated="userCreated" />
            </div>
        </fj-base-header>
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
    </fj-base-container>
</template>

<script>
export default {
    name: 'FjordUsers',
    props: {
        roles: {
            type: Array
        },
        users: {
            type: Array
        },
        user_roles: {
            type: Array
        }
    },
    data() {
        return {
            items: [],
            data: {},
            test: null
        };
    },
    beforeMount() {
        this.items = this.users;

        for (var i = 0; i < this.users.length; i++) {
            let user = this.users[i];
            this.initUser(user);
        }
    },
    methods: {
        userHasRole(role, user) {
            return (
                _.size(
                    _.filter(this.user_roles, {
                        role_id: role.id,
                        model_id: user.id
                    })
                ) > 0
            );
        },
        async toggleRole(role, user) {
            let payload = {
                role,
                user
            };
            let respose = await axios.put('/user_roles', payload);

            let current_user = JSON.parse(JSON.stringify(this.data[user.name]));

            for (var current_role in current_user) {
                if (current_user.hasOwnProperty(current_role)) {
                    if (current_role != role.name) {
                        this.$set(this.data[user.name], current_role, false);
                    }
                }
            }
        },
        initUser(user) {
            this.$set(this.data, user.name, {});
            for (var p = 0; p < this.roles.length; p++) {
                let role = this.roles[p];
                this.$set(
                    this.data[user.name],
                    role.name,
                    this.userHasRole(role, user)
                );
            }
        },
        userCreated(user) {
            this.items.push(user);
            this.initUser(user);
        }
    }
};
</script>
