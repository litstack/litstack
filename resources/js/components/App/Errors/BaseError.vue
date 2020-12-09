<template>
    <lit-container
        class="d-flex justify-content-around align-items-center"
        style="height: 100vh;"
    >
        <div class="text-center">
            <lit-lit-lightning />
            <div class="text-muted" style="padding: 10px;">
                {{ message }}
            </div>
        </div>
    </lit-container>
</template>
<script>
export default {
    name: 'BaseError',
    props: {
        code: {
            type: String,
            required: true,
        },
        message: {
            type: String,
            required: true,
        },
    },
};
</script>
<style lang="scss">
@import '@lit-sass/_variables';
</style>
