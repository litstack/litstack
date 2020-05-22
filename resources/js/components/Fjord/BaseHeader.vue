<template>
    <b-row class="fj-container-header mb-4">
        <b-col cols="12" class="mt-2 mb-2">
            <slot>
                <h3 v-if="title">
                    {{ title }}
                </h3>
            </slot>
        </b-col>
        <b-col
            cols="12"
            class="d-flex justify-content-between fj-base-container-header__actions"
        >
            <div v-if="!$slots['actions'] && $slots['actions-right']" />

            <slot name="actions" />

            <slot name="actions-right" />
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
            }
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';

.fj-container-header {
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
        > div.indent {
            &:first-child {
                margin-left: -($btn-padding-x);
            }
            &:last-child {
                margin-right: -($btn-padding-x);
            }

            &.sm {
                &:first-child {
                    margin-left: -($btn-padding-x-sm);
                }
                &:last-child {
                    margin-right: -($btn-padding-x-sm);
                }
            }
        }
    }
}
</style>
