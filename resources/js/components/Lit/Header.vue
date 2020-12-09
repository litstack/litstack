<template>
    <b-row class="lit-container-header mb-4">
        <b-col cols="12" class="mt-2 mb-2">
            <slot>
                <h3 v-if="title">
                    {{ title }}
                </h3>
            </slot>
        </b-col>
        <b-col
            cols="12"
            class="d-flex justify-content-between lit-container-header__actions"
        >
            <div>
                <slot name="actions" />
            </div>

            <div>
                <slot name="actions-right" />
            </div>
        </b-col>
    </b-row>
</template>

<script>
export default {
    name: 'Header',
    props: {
        title: {
            type: String,
            default() {
                return '';
            },
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';

.lit-container-header {
    a {
        &:hover {
            text-decoration: none;
        }
    }

    h3 {
        position: relative;

        small {
            font-size: $font-size-base * 0.75;
        }
    }

    &__navigations,
    &__actions {
        > div {
            &:first-child > * {
                margin-right: map-get($spacers, 2);
            }
            &:last-child > * {
                margin-left: map-get($spacers, 2);
            }
        }
    }
}
</style>
