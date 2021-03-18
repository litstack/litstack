<template>
    <b-modal id="lit-page-preview" size="full" hide-footer>
        <template slot="modal-header" slot-scope="{ close }">
            <!-- Emulate built in modal header close button action -->

            <div class="d-flex justify-content-between w-100">
            <div class="device-btns">
                <b-button
                    class="mr-2 btn-square"
                    :variant="
                        device == 'mobile' ? 'secondary' : 'outline-secondary'
                    "
                    @click="setDevice('mobile')"
                >
                    <lit-fa-icon fas icon="mobile-alt" />
                </b-button>
                <b-button
                    class="mr-2 btn-square"
                    :variant="
                        device == 'tablet' ? 'secondary' : 'outline-secondary'
                    "
                    @click="setDevice('tablet')"
                >
                    <lit-fa-icon fas icon="tablet-alt" />
                </b-button>
                <b-button
                    @click="setDevice('desktop')"
                    :variant="
                        device == 'desktop' ? 'secondary' : 'outline-secondary'
                    "
                    class="btn-square"
                >
                    <lit-fa-icon fas icon="desktop" />
                </b-button>
            </div>

            <div>
                <lit-crud-language variant="secondary" v-if="!uniqueRoutes"/>
            </div>

            <button
                type="button"
                aria-label="Close"
                class="close p-0 m-1"
                @click="close()"
            >
                ×
            </button>
            </div>
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
        routes: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            device: 'desktop',
        };
    },
    beforeMount() {
        this.device = Lit.config.crud.preview.default_device;
    },
    methods: {
        setDevice(device) {
            this.device = device;
        },
    },
    computed: {
        ...mapGetters(['config', 'language']),
        showRoute() {
            if (!this.route.includes('//')) {
                return this.route;
            }
            return this.route.split('//')[1];
        },
        route() {
            return this.routes[this.language];
        },
        uniqueRoutes() {
            let first = this.routes[(Object.keys(this.routes)[0])];

            for(let locale in this.routes) {
                if(this.routes[locale] != first) {
                    return false;
                }
            }

            return true;
        }
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
#lit-page-preview {
    .device {
        margin: 0 auto;
        transition: 0.25s width cubic-bezier(0.91, -0.13, 0.68, 0.79);

        .controls {
            position: relative;
            background: #37383d;
            display: flex;
            padding: 1.5rem;

            .nav {
                margin: 0 80px;
                width: 100%;
                height: 30px;
                background: $gray-900;
                padding: 0 1rem;
                color: white;
                border-radius: $border-radius;
                display: flex;
                align-items: center;
            }

            .btns {
                position: absolute;
                margin-left: 5px;
                margin-right: 5px;
                height: 30px;
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
        &.mobile,
        &.tablet,
        &.desktop {
            height: 100%;
            background: $gray-800;
            border-radius: $border-radius;
            overflow: hidden;
        }
        &.mobile,
        &.tablet {
            border: 16px solid #37383d;
        }
        &.mobile {
            width: calc(375px + 32px);

            .display {
                height: 100%;
            }

            .controls {
                display: none;
            }
        }
        &.tablet {
            width: calc(1024px + 32px);
            .controls {
                display: none;
            }
            .display {
                height: 100%;
            }
        }
        &.desktop {
            width: 90%;
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
</style>
