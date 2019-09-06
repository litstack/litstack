<template>
    <b-row class="fj-container-header mb-4">
        <b-col cols="12" class="fj-container-header__navigations d-flex justify-content-between">

            <div class="indent sm">
                <b-button
                    size="sm"
                    variant="transparent"
                    v-if="back"
                    :href="`${baseURL}${back}`">
                    <fa-icon icon="angle-left"/> {{ backText ? backText : 'back' }}
                </b-button>
            </div>


            <div class="indent">
                <!--
                <b-button variant="transparent">
                    HII
                </b-button>
                -->
            </div>


        </b-col>
        <b-col cols="12" class="d-flex justify-content-between">
            <div>
                <h3 class="mt-2 mb-2" v-if="title">
                    {{ title }}
                </h3>
            </div>
        </b-col>
        <b-col cols="12" class="d-flex justify-content-between fj-container-header__actions">

            <div v-if="!$slots['actions'] && $slots['actions-right']"/>

            <slot name="actions"/>

            <slot name="actions-right"/>

        </b-col>
    </b-row>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'Header',
    props: {
        title: {
            type: String
        },
        back: {
            type: String
        },
        backText: {
            type: String
        }
    },
    computed: {
        ...mapGetters(['baseURL'])
    },
}
</script>

<style lang="scss">
@import '../../sass/_variables';

.fj-container-header{
    a {
        &:hover{
            text-decoration: none;
        }
    }

    &__navigations, &__actions{
        > div.indent{
            &:first-child{
                margin-left: -($btn-padding-x);
            }
            &:last-child{
                margin-right: -($btn-padding-x);
            }

            &.sm{
                &:first-child{
                    margin-left: -($btn-padding-x-sm);
                }
                &:last-child{
                    margin-right: -($btn-padding-x-sm);
                }
            }
        }
    }
}
</style>
