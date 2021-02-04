<template>
    <b-row class="lit-page-navigation" :class="{ 'can-save': canSave }">
        <b-col
            cols="12"
            class="d-flex justify-content-end justify-content-lg-between align-items-center lit-page-navigation__container"
        >
            <div class="lit-page-navigation-left d-none d-lg-block">
                <div
                    v-if="breadcrumb.length > 0"
                    class="lit-page-navigation__go_back d-inline-block small mr-3"
                >
                    <!-- href="`${Lit.baseURL}${back}`" -->
                    <!-- <lit-fa-icon icon="list-ul" class="mr-1" /> -->
                    <!-- <span v-html="backText ? backText : 'Go Back'" /> -->
                    <template v-for="(item, i) in breadcrumb">
                        <a
                            :href="`${Lit.baseURL}${item.url}`"
                            class="text-secondary"
                            :key="i"
                            >{{ linktext(item) }}</a
                        >

                        <template v-if="i < breadcrumb.length - 1">
                            /
                        </template>
                    </template>
                    <span v-if="current">
                        / <strong>{{ stripTags(current) }}</strong>
                    </span>
                </div>
                <div class="d-inline-block">
                    <slot name="left" />
                </div>
            </div>

            <div
                class="d-flex lit-save-animate lit-page-navigation-right"
                :style="wrapperStyle"
                :class="{ loaded: loaded }"
            >
                <b-dropdown
                    variant="outline-secondary"
                    size="md"
                    class="dropdown-md-square"
                    :class="{ disabled: _.isEmpty(this.$slots.controls) }"
                    v-bind:disabled="_.isEmpty(this.$slots.controls)"
                    right
                    no-caret
                >
                    <template v-slot:button-content>
                        <lit-fa-icon icon="ellipsis-h" />
                    </template>

                    <slot name="controls" />
                </b-dropdown>
                <slot name="right" />
                <div
                    class="lit-save-button lit-save-animate"
                    :class="{ loaded: loaded }"
                    :style="buttonStyle"
                >
                    <b-button
                        variant="outline-secondary"
                        class="btn-square mr-3"
                        v-b-tooltip
                        :title="__('base.undo_changes')"
                        @click="Lit.bus.$emit('cancelSave')"
                    >
                        <lit-fa-icon icon="undo" />
                    </b-button>
                    <b-button
                        variant="primary"
                        size="md"
                        :disabled="!canSave"
                        class="text-capitalize"
                        @click="Lit.bus.$emit('save')"
                    >
                        {{ __('base.save') }}
                    </b-button>
                </div>
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
            type: [String, Boolean],
        },
        backText: {
            type: String,
        },
        breadcrumb: {
            type: Array,
            default() {
                return [];
            },
        },
        current: {
            type: String,
            default: null,
        },
        controls: {
            type: Array,
            default() {
                return [];
            },
        },
    },
    data() {
        return {
            b: {},
            offset: 0,
            loaded: false,
        };
    },
    mounted() {
        let self = this;
        this.scrollBehavior();
        this.saveShortCut();

        this.$nextTick(async function() {
            await this.saveButtonIsRendered('.lit-save-button');
            let ww = window.innerWidth;
            let button = document
                .querySelector('.lit-save-button')
                .getBoundingClientRect();
            let offset = ww - button.right;

            this.b = button;
            this.offset = Math.ceil(offset);

            setTimeout(() => {
                this.loaded = true;
            }, 10);
        });
    },
    methods: {
        linktext(item) {
            return (item.breadcrumb || item.title).trim();
        },
        stripTags(string) {
            return string.replace(/(<([^>]+)>)/gi, '');
        },
        scrollBehavior() {
            document
                .querySelector('div#litstack > main')
                .addEventListener('scroll', e => {
                    let header = document.querySelector('.lit-page-navigation');
                    let toasterSlot = document.querySelector('.b-toaster-slot');

                    if (!header) {
                        return;
                    }

                    if (header.getBoundingClientRect().top == 0) {
                        header.classList.add('sticky');
                        if (toasterSlot) {
                            toasterSlot.classList.add('sticky');
                        }
                    } else {
                        header.classList.remove('sticky');
                        if (toasterSlot) {
                            toasterSlot.classList.remove('sticky');
                        }
                    }
                });
        },
        /**
         * cmd + s shortcut.
         */
        saveShortCut() {
            document.addEventListener(
                'keydown',
                e => {
                    if (
                        (window.navigator.platform.match('Mac')
                            ? e.metaKey
                            : e.ctrlKey) &&
                        e.keyCode == 83
                    ) {
                        e.preventDefault();
                        if (this.canSave) {
                            Lit.bus.$emit('save');
                        }
                    }
                },
                false
            );
        },
        /**
         * Used with elementIsRendered.
         */
        waitUntilSaveButton(selector, scope, resolve, reject) {
            let loopCount = 0;
            let maxLoops = 100;

            // Loops until element exists in DOM or loop times out
            function checkForElement() {
                if (loopCount === maxLoops) {
                    loopCount = 0;
                    return reject('Timed out waiting for element to render');
                }

                let el = scope.querySelector(selector);

                setTimeout(() => {
                    if (el.getBoundingClientRect().width > 0) {
                        loopCount = 0;
                        return resolve(el);
                    } else {
                        loopCount++;
                        checkForElement();
                    }
                }, 100);
            }

            checkForElement();
        },

        /**
         * Returns a resolved Promise once the selector returns an element.
         * Useful for when we need to perform an action only when an element is
         * in the DOM
         */
        saveButtonIsRendered(selector, scope = document) {
            return new Promise((resolve, reject) => {
                //start the loop
                return this.waitUntilSaveButton(
                    selector,
                    scope,
                    resolve,
                    reject
                );
            });
        },
    },

    computed: {
        ...mapGetters(['canSave']),
        wrapperStyle() {
            let offset = this.canSave ? 0 : `calc(${this.b.width}px + 1rem)`;
            return {
                transform: `translateX(${offset})`,
            };
        },
        buttonStyle() {
            let offset = this.canSave ? '0' : '50vw';
            return {
                transform: `translateX(${offset})`,
            };
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';

.lit-page-navigation {
    height: $topbar-height;
    // padding-top: $page-nav-padding-y;
    // padding-bottom: $page-nav-padding-y;
    position: sticky;
    top: 0;
    background: $body-bg;
    z-index: 5;
    box-shadow: 0 0 0 0 rgba(188, 188, 188, 0.51);
    transition: 0.2s all ease-in;

    &.sticky {
        box-shadow: 0 17px 14px -16px rgba(188, 188, 188, 0.51);
    }

    &__go_back {
        color: $secondary;
        transform: translateY(2px);
        &:hover {
            color: $gray-700;
            text-decoration: none;
        }
    }

    &-left {
        > div > * {
            // margin-right: map-get($spacers, 2);
        }
    }

    .lit-save-button {
        transform: translateX(50vw);
        display: flex;
        //box-shadow: 0px 13px 12px -7px rgba(102, 123, 144, 0.5);
    }
    .lit-save-animate {
        opacity: 0;
        &.loaded {
            opacity: 1;
            transition: 0.25s transform cubic-bezier(0.91, -0.13, 0.68, 0.79);
        }
    }

    .lit-page-navigation__container {
        > div {
            &:first-child {
                > div,
                > .btn {
                    // margin-right: map-get($spacers, 3);
                }
            }
            &:last-child {
                > div,
                > .btn {
                    margin-left: map-get($spacers, 3);
                }
            }
        }
        .lit-page-navigation__go_back {
            // margin-left: -$btn-padding-x-sm;
        }
    }
    @media (max-width: map-get($grid-breakpoints, $nav-breakpoint-mobile)) {
        width: 100vw;
        position: fixed;
        bottom: 0;
        top: unset;
        height: $nav-height-mobile;

        margin: 0;
        left: 0;
        z-index: $zindex-fixed;
        box-shadow: 0 -17px 14px -16px rgba(188, 188, 188, 0.51);

        &.can-save {
            .lit-page-navigation-right > :not(.lit-save-button) {
                display: none;
            }
        }
    }
}
</style>
