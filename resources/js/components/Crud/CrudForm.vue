<template>
    <fj-container :fluid="config.expand ? 'fluid' : 'lg'">
        <fj-navigation
            :back="backRoute"
            :back-text="config.names.plural"
            :controls="slots.navControls"
        >
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
            <h3 class="d-flex justify-content-between align-items-baseline">
                {{ config.names.singular }}
                <small
                    class="text-secondary"
                    v-if="model.last_edit"
                    v-html="
                        __('crud.last_edited', {
                            time: model.last_edit.time,
                            user: `${model.last_edit.user.first_name} ${model.last_edit.user.last_name}`
                        })
                    "
                />
            </h3>
            <div
                slot="actions"
                class="pt-3"
                v-if="slots.headerControls.length > 0"
            >
                <fj-slot
                    v-for="(component, key) in slots.headerControls"
                    :key="key"
                    v-bind="component"
                    :config="config"
                    :model="model"
                />
            </div>
        </fj-header>

        <b-row>
            <fj-col :width="12">
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
            </fj-col>
        </b-row>
    </fj-container>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudForm',
    props: {
        crudModel: {
            type: Object,
            required: true
        },
        config: {
            type: [Array, Object],
            required: true
        },
        slots: {
            required: true,
            type: Object
        },
        nearItems: {
            type: Object
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
        async reloadModel() {
            let response;
            try {
                response = await axios.get(
                    `${this.config.route_prefix}/${this.model.id}/load`
                );
            } catch (e) {
                console.log(e);
                return;
            }
            this.model = this.crud(response.data);
        },
        saved(results) {
            let result;
            result = results.findSucceeded(
                'put',
                `${this.config.route_prefix}/${this.model.id}`
            );
            if (result) {
                this.model = this.crud(result.data);
            }
            result = results.findSucceeded(
                'post',
                `${this.config.route_prefix}`
            );
            if (result) {
                this.model = this.crud(result.data);
            }

            if (
                window.location.pathname.split('/').pop() == 'create' &&
                this.model.id
            ) {
                setTimeout(() => {
                    window.location.replace(`${this.model.id}/edit`);
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

        Fjord.bus.$on('saved', this.saved);
        Fjord.bus.$on('field:updated', this.reloadModel);
    },
    mounted() {
        this.scrollToFormFieldFromHash();
    }
};
</script>
