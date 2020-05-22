<template>
    <div class="mb-3 fj-index-form">
        <slot />
    </div>
</template>

<script>
export default {
    name: 'BaseIndexTableForm'
};
</script>
<style lang="scss">
@import '@fj-sass/_variables';
.fj-index-form {
    display: flex;
    flex-direction: row;
    align-items: stretch;
    > div {
        margin-left: map-get($spacers, 1);
        margin-right: map-get($spacers, 1);

        &:last-child {
            margin-right: 0;
        }
        &:first-child {
            flex: 1;
            margin-left: 0;
        }
    }
}
</style>
