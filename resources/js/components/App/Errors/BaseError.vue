<template>
    <fj-container
        class="d-flex justify-content-around align-items-center"
        style="height: 100vh"
    >
        <div class="text-center">
            <fj-fjord-lightning />
            <div class="text-muted" style="padding: 10px;">
                {{ message }}
            </div>
        </div>
    </fj-container>
</template>
<script>
export default {
    name: 'BaseError',
    props: {
        code: {
            type: String,
            required: true
        },
        message: {
            type: String,
            required: true
        }
    }
};
</script>
<style lang="scss">
@import '@fj-sass/_variables';
</style>
