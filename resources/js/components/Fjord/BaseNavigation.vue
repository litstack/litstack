<template>
    <b-row class="fj-page-navigation">
        <b-col
            cols="12"
            class="d-flex justify-content-between align-items-center fj-page-navigation__container"
        >
            <div>
                <b-button
                    size="sm"
                    variant="link"
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
                <b-dropdown
                    variant="outline-secondary"
                    size="md"
                    class="dropdown-md-square"
                    v-bind:disabled="_.isEmpty(controls)"
                    no-caret
                >
                    <template v-slot:button-content>
                        <fa-icon icon="ellipsis-h" />
                    </template>

                    <fj-slot
                        v-for="(component, key) in controls"
                        :key="key"
                        v-bind="component"
                    />
                </b-dropdown>
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
        },
        controls: {
            type: Array,
            default() {
                return [];
            }
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
        },
        // Used with elementIsRendered
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

        // Returns a resolved Promise once the selector returns an element
        // Useful for when we need to perform an action only when an element is in the DOM
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

        this.$nextTick(async function() {
            await this.saveButtonIsRendered('.fj-save-button');
            let ww = window.innerWidth;
            let button = document
                .querySelector('.fj-save-button')
                .getBoundingClientRect();
            let offset = ww - button.right;

            this.b = button;
            this.offset = Math.ceil(offset);
        });
    },
    computed: {
        ...mapGetters(['canSave']),
        wrapperStyle() {
            let offset = this.canSave ? 0 : `calc(${this.b.width}px + 1rem)`;
            return {
                transform: `translateX(${offset})`
            };
        },
        buttonStyle() {
            let offset = this.canSave ? '0' : '50vw';
            return {
                transform: `translateX(${offset})`
            };
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';

.fj-page-navigation {
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
        &:hover {
            color: $gray-700;
            text-decoration: none;
        }
    }

    .fj-save-button {
        transform: translateX(50vw);
        box-shadow: 0px 13px 12px -7px rgba(102, 123, 144, 0.5);
    }
    .fj-save-animate {
        transition: 0.25s all cubic-bezier(0.91, -0.13, 0.68, 0.79);
    }

    .fj-page-navigation__container {
        > div {
            &:first-child {
                > div,
                > .btn {
                    margin-right: map-get($spacers, 3);
                }
            }
            &:last-child {
                > div,
                > .btn {
                    margin-left: map-get($spacers, 3);
                }
            }
        }
        .fj-page-navigation__go_back {
            margin-left: -$btn-padding-x-sm;
        }
    }
}
</style>
