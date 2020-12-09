<template>
    <b-dropdown-item @click="logout">
        <div class="mr-2 d-inline-block lit-topbar__icon">
            <lit-fa-icon icon="sign-out-alt" />
        </div>

        Logout
    </b-dropdown-item>
</template>

<script>
import Axios from 'axios';
export default {
    name: 'Logout',
    props: {
        url: {
            type: String,
            required: true,
        },
    },
    methods: {
        async logout() {
            try {
                await axios.post(this.url);
                location.reload();
            } catch (error) {}
        },
    },
};
</script>
