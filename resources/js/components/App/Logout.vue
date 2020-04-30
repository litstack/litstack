<template>
    <b-dropdown-item @click="logout">
        <fa-icon icon="sign-out-alt" class="mr-3" />
        logout
    </b-dropdown-item>
</template>

<script>
import Axios from 'axios';
export default {
    name: 'Logout',
    props: {
        url: {
            type: String,
            required: true
        }
    },
    methods: {
        async logout() {
            try {
                await axios.post(this.url);
                location.reload();
            } catch (error) {}
        }
    }
};
</script>
