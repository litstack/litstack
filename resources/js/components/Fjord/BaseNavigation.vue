<template>
    <b-row class="fj-page-navigation">
        <b-col
            cols="12"
            class="d-flex justify-content-between fj-page-navigation__container"
        >
            <div>
                <b-button
                    size="sm"
                    variant="transparent"
                    v-if="back"
                    class="mr-2 fj-page-navigation__go_back"
                    :href="`${Fjord.baseURL}${back}`"
                >
                    <fa-icon icon="list-ul" class="mr-1" />
                    {{ backText ? backText : 'Go Back' }}
                </b-button>
                <div class="d-inline-block">
                    <slot name="left" />
                </div>
            </div>

            <div class="d-flex fj-save-animate" :style="wrapperStyle">
                <b-button
                    variant="outline-secondary"
                    size="md"
                    class="fj-page-navigation__controls"
                >
                    <fa-icon icon="ellipsis-h" style="margin: 0 4px;" />
                </b-button>
                <slot name="right" />
                <b-button
                    class="fj-save-button fj-save-animate"
                    variant="primary"
                    size="md"
                    :disabled="!canSave"
                    @click="saveAll"
                    :style="buttonStyle"
                >
                    {{ $t('fj.save') }}
                </b-button>
            </div>
        </b-col>
    </b-row>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'Navigation',
    props: {
        back: {
            type: [String, Boolean]
        },
        backText: {
            type: String
        }
    },
    data() {
        return {
            b: {},
            offset: 0
        };
    },
    methods: {
        async saveAll() {
            try {
                await this.$store.dispatch('save');
            } catch (e) {
                console.log(e);
                return;
            }
            this.$bvToast.toast(this.$t('fj.saved'), {
                variant: 'success'
            });
        }
    },
    mounted() {
        let self = this;
        document.addEventListener(
            'keydown',
            function(e) {
                if (
                    (window.navigator.platform.match('Mac')
                        ? e.metaKey
                        : e.ctrlKey) &&
                    e.keyCode == 83
                ) {
                    e.preventDefault();
                    if (self.canSave) {
                        self.saveAll();
                    }
                }
            },
            false
        );

        let ww = window.innerWidth;
        let button = document
            .querySelector('.fj-save-button')
            .getBoundingClientRect();
        let offset = ww - button.right;

        this.b = button;
        this.offset = Math.ceil(offset);
    },
    computed: {
        ...mapGetters(['canSave']),
        wrapperStyle() {
            let offset = this.canSave ? 0 : this.b.width + 16;
            return {
                transform: `translateX(${offset}px)`
            };
        },
        buttonStyle() {
            let offset = this.canSave ? 0 : this.offset;
            return {
                transform: `translateX(${offset}px)`
            };
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';

$row-margin-x: -$grid-gutter-width / 2;

.fj-page-navigation {
    margin: 0 calc(#{$container-padding-x} + #{$row-margin-x});
    padding: $page-nav-padding-y 0;
    position: sticky;
    top: 0;
    background: $body-bg;
    z-index: 1;
    box-shadow: 0 0 0 0 rgba(188, 188, 188, 0.51);
    transition: 0.2s all ease-in;

    &.sticky {
        box-shadow: 0 17px 14px -16px rgba(188, 188, 188, 0.51);
    }

    .fj-save-button {
        box-shadow: 0px 13px 12px -7px rgba(102, 123, 144, 0.5);
    }
    .fj-save-animate {
        transition: 0.25s all cubic-bezier(0.91, -0.13, 0.68, 0.79);
    }

    .fj-page-navigation__controls {
        width: 46px;

        padding-left: 0;
        padding-right: 0;
        text-align: center;
    }

    .fj-page-navigation__container {
        > div {
            &:first-child {
                > div,
                > .btn {
                    margin-right: map-get($spacers, 2);
                }
            }
            &:last-child {
                > div,
                > .btn {
                    margin-left: map-get($spacers, 2);
                }
            }
        }
        .fj-page-navigation__go_back {
            margin-left: -$btn-padding-x-sm;
        }
    }
}
</style>
