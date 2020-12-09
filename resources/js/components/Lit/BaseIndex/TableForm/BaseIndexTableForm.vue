<template>
    <div class="mb-3 lit-index-form">
        <slot />
    </div>
</template>

<script>
export default {
    name: 'BaseIndexTableForm',
};
</script>
<style lang="scss">
@import '@lit-sass/_variables';
.lit-index-form {
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
