<template>
    <b-container :class="`lit-container`" :fluid="fluid">
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
            },
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';

.lit-container {
    margin: 0 auto;
    padding: (/*$container-padding-y*/ $page-nav-padding-y) $container-padding-x
        $container-padding-y $container-padding-x !important;
    //padding: 0 $container-padding-x $container-padding-y $container-padding-x !important;
    @media (max-width: map-get($grid-breakpoints, $nav-breakpoint-mobile)) {
        padding: 1rem !important;
        max-width: unset;
    }
}
</style>
