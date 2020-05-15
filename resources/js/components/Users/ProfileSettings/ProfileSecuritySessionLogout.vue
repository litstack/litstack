<template>
    <span>
        <b-button size="sm" @click="logout">
            {{ __('base.logout').capitalize() }}
        </b-button>
    </span>
</template>

<script>
export default {
    name: 'ProfileSecuritySessionLogout',
    props: {
        item: {
            required: true,
            type: Object
        }
    },
    methods: {
        async logout() {
            let response;
            try {
                response = await axios.post('logout/session', {
                    id: this.item.id
                });
            } catch (e) {
                console.log(e);
                return;
            }

            if (this.item.is_current) {
                window.location.reload();
            }
            this.$emit('reload');
        }
    }
};
</script>
