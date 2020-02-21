<template>
    <b-modal id="fj-page-preview" hide-footer>
        <template slot="modal-header" slot-scope="{ close }">
            <!-- Emulate built in modal header close button action -->

            <div></div>

            <div class="device-btns">
                <b-button class="mr-2" @click="setDevice('mobile')">
                    <fa-icon fas icon="mobile-alt" />
                </b-button>
                <b-button class="mr-2" @click="setDevice('tablet')">
                    <fa-icon fas icon="tablet-alt" />
                </b-button>
                <b-button @click="setDevice('desktop')">
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
        this.device = this.config.crud.preview.default_device;
    },
    methods: {
        setDevice(device) {
            this.device = device;
        }
    },
    computed: {
        ...mapGetters(['config'])
    }
};
</script>

<style lang="scss">
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
                &.mobile {
                    position: absolute;
                    width: 375px;
                    height: calc(100vh - 130px);
                    max-height: 812px;
                    background: gray;
                    padding: 40px 20px;
                    border-radius: 5px;
                }
                &.tablet {
                    position: absolute;
                    width: 1024px;
                    height: calc(100vh - 130px);
                    max-height: 768px;
                    background: gray;
                    padding: 40px 20px;
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
                    height: 100%;
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
