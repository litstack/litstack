<template>
    <fj-container :fluid="config.expand ? 'fluid' : 'lg'">
        <fj-navigation :back="backRoute" :back-text="config.names.plural">
            <fj-crud-show-near-items
                slot="left"
                v-if="nearItems"
                :route-prefix="config.route_prefix"
                :nearItems="nearItems"
            />
            <fj-crud-language slot="right" />
            <!--
                <div slot="controls" class="pt-1" v-if="controls.length > 0">
                    <hr />
                    <components
                        v-for="(component, key) in controls"
                        :key="key"
                        :is="component"
                        :nearItems="nearItems"
                        v-if="crud.model"
                        :model="crud.model"
                    />
                </div>
                -->
        </fj-navigation>

        <fj-header>
            <h3 class="d-flex justify-content-between">
                {{ config.names.singular }}
                <small
                    class="text-secondary"
                    v-if="model.last_edit"
                    v-html="
                        __('crud.last_edited', {
                            time: model.last_edit.time,
                            user: model.last_edit.user.name
                        })
                    "
                />
            </h3>
            <div slot="actions" class="pt-3" v-if="headerComponents.length > 0">
                <components
                    v-for="(component, key) in headerComponents"
                    :key="key"
                    :is="component"
                    :config="config"
                    v-if="model"
                    :model="model"
                />
            </div>
        </fj-header>
        <b-row>
            <b-col cols="12">
                <b-row class="fjord-form">
                    <components
                        v-for="(component, key) in config.form.components"
                        :key="key"
                        :is="component.name"
                        v-bind="component.props"
                        :model="model"
                        :config="config"
                    />
                </b-row>
            </b-col>
        </b-row>
    </fj-container>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudShow',
    props: {
        crudModel: {
            type: Object,
            required: true
        },
        config: {
            type: [Array, Object],
            required: true
        },
        nearItems: {
            type: Object
        },
        headerComponents: {
            type: Array,
            required: true
        },
        backRoute: {
            type: [String, Boolean],
            default() {
                return false;
            }
        },
        actions: {
            type: Array,
            default: () => {
                return [];
            }
        },
        controls: {
            type: Array,
            default: () => {
                return [];
            }
        },
        content: {
            type: Array,
            default: () => {
                return [];
            }
        }
    },
    data() {
        return {
            model: {}
        };
    },
    methods: {
        saved() {
            if (window.location.pathname.split('/').pop() == 'create') {
                setTimeout(() => {
                    window.location.replace(`${this.crud.model.id}/edit`);
                }, 1);
            }
        },
        scrollToFormFieldFromHash() {
            if (!window.location.hash) {
                return;
            }
            let hash = window.location.hash.replace('#', '');
            let elements = document.getElementsByClassName(
                `fj-form-item-${hash}`
            );
            if (elements.length < 1) {
                return;
            }
            // Scroll to first one.
            let element = elements[0];
            let pos = element.style.position;
            let top = element.style.top;
            element.style.position = 'relative';
            element.style.top = '-30px';
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            element.style.top = top;
            element.style.position = pos;
        },
        setSavedModel(responses) {
            for (let i in responses) {
                let response = responses[i];
                if (
                    response.config.url ==
                        `${this.config.route_prefix}/${this.model.id}` &&
                    response.config.method == 'put'
                ) {
                    this.model = this.crud(response.data);
                }
            }
        }
    },
    computed: {
        isCreate() {
            return window.location.pathname.split('/').pop() == 'create';
        }
    },
    beforeMount() {
        this.model = this.crud(this.crudModel);

        this.$store.commit('SET_CONFIG', this.config);

        Fjord.bus.$on('saved', this.setSavedModel);
    },
    mounted() {
        this.scrollToFormFieldFromHash();
    }
};
</script>
