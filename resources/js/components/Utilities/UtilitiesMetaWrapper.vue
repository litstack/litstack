<template>
    <lit-col class="d-flex">
        <div
            class="meta-wrapper"
            :style="`max-width: calc(${googleMetaMaxWidth} + 2rem + 2px)`"
        >
            <b-row>
                <slot />
            </b-row>
        </div>
    </lit-col>
</template>

<script>
export default {
    name: 'UtilitiesMetaWrapper',
    props: {
        googleMetaMaxWidth: {
            type: [Number, String],
            default() {
                return '520px';
            },
        },
    },
};
</script>
