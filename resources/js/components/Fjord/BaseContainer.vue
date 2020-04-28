<template>
    <b-container :fluid="size" :class="`fjord-container`">
        <slot />
    </b-container>
</template>

<script>
export default {
    name: 'BaseContainer',
    props: {
        size: {
            type: String,
            default() {
                return 'lg';
            }
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';

.fjord-container {
    margin: 0 auto;
    padding: 30px !important;
}
</style>
