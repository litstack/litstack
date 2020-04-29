<template>
    <div class="fj-page">
        <slot />
    </div>
</template>

<script>
export default {
    name: 'Page',
    props: {
        fluid: {
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

.fj-page {
    padding-top: $container-padding-y;
}
</style>
