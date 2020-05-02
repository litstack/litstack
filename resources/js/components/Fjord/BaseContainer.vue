<template>
    <b-container :class="`fj-container`" :fluid="fluid">
        <slot />
    </b-container>
</template>

<script>
export default {
    name: 'Container',
    props: {
        fluid: {
            type: String,
            default() {
                return 'sm';
            }
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';

.fj-container {
    margin: 0 auto;
    padding: ($container-padding-y - $page-nav-padding-y) $container-padding-x
        $container-padding-y $container-padding-x !important;
}
</style>
