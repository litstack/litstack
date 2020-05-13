<template>
    <b-modal id="fj-page-preview" hide-footer>
        <template slot="modal-header" slot-scope="{ close }">
            <!-- Emulate built in modal header close button action -->

            <div></div>

            <div class="device-btns">
                <b-button class="mr-2 btn-square" @click="setDevice('mobile')">
                    <fa-icon fas icon="mobile-alt" />
                </b-button>
                <b-button class="mr-2 btn-square" @click="setDevice('tablet')">
                    <fa-icon fas icon="tablet-alt" />
                </b-button>
                <b-button @click="setDevice('desktop')" class="btn-square">
                    <fa-icon fas icon="desktop" />
                </b-button>
            </div>

            <div></div>

            <button
                type="button"
                aria-label="Close"
                class="close"
                @click="close()"
            >
                ×
            </button>
        </template>

        <div :class="`device ${device}`">
            <div class="controls">
                <div class="btns">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="nav">
                    {{ showRoute }}
                </div>
            </div>
            <div class="display">
                <iframe :src="route" />
            </div>
        </div>
    </b-modal>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'PagePreview',
    props: {
        route: {
            required: true,
            type: String
        }
    },
    data() {
        return {
            device: 'desktop'
        };
    },
    beforeMount() {
        this.device = Fjord.config.crud.preview.default_device;
    },
    methods: {
        setDevice(device) {
            this.device = device;
        }
    },
    computed: {
        ...mapGetters(['config']),
        showRoute() {
            if (!this.route.includes('//')) {
                return this.route;
            }
            return this.route.split('//')[1];
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';
#fj-page-preview {
    position: relative;

    .modal-dialog {
        margin: 0;
        max-width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;

        .modal-header {
            position: relative;
            .device-btns {
                .btn {
                }
            }
            .close {
                position: absolute;
                bottom: 0;
                top: 0;
                right: 0;
                margin: 0;
                padding: 0 -1rem 0 -1rem;
            }
        }

        .modal-content {
            border-radius: 0;
            border: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;

            .modal-body {
                padding: 0;
                position: relative;
                display: flex;
                justify-content: space-around;
                align-items: center;
            }

            .device {
                .controls {
                    background: $gray-400;
                    display: flex;
                    height: 40px;

                    .nav {
                        height: 30px;
                        background: $gray-600;
                        margin: 5px 0;
                        padding: 0 10px;
                        color: white;
                        border-radius: 2px;
                        display: flex;
                        align-items: center;
                    }

                    .btns {
                        margin-left: 5px;
                        margin-right: 5px;
                        height: 40px;
                        width: 65px;
                        display: flex;
                        align-items: center;
                        justify-content: space-around;
                        div {
                            height: 10px;
                            width: 10px;
                            background: #f4be41;
                            border-radius: 100%;

                            &:first-child {
                                background: #ec664f;
                            }
                            &:last-child {
                                background: #5dcb3d;
                            }
                        }
                    }
                }
                &.mobile {
                    position: absolute;
                    width: 375px;
                    height: calc(100vh - 130px);
                    max-height: 812px;
                    background: $gray-400;
                    padding: 20px 20px 40px 20px;
                    border-radius: 5px;

                    .display {
                        height: calc(100% - 50px);
                    }

                    .controls {
                        height: 70px;
                        display: block;

                        .btns {
                            height: 30px;
                        }

                        .nav {
                            width: 100%;
                        }
                    }
                }
                &.tablet {
                    position: absolute;
                    width: 1024px;
                    height: calc(100vh - 130px);
                    max-height: 768px;
                    background: $gray-400;
                    padding: 20px 20px 40px 20px;
                    border-radius: 5px;
                }
                &.desktop {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                }

                .display {
                    position: relative;
                    height: calc(100% - 20px);
                    width: 100%;
                    background: white;

                    iframe {
                        border: none;
                        width: 100%;
                        height: 100%;
                    }
                }
            }
        }
    }
}
</style>
