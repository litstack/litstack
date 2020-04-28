<template>
    <fj-base-container>
        <fj-base-header
            :title="config.names.singular"
            :back="backRoute"
            :back-text="config.names.plural"
        >
            <!--
            <fj-crud-show-near-items
                slot="navigation"
                v-if="nearItems"
                :config="config"
                :nearItems="nearItems"
            />

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
            -->
        </fj-base-header>
        <b-row>
            <b-col cols="12" md="9" order-md="1">
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

            <b-col cols="12" md="3" order-md="2" class="pb-4 mb-md-0">
                <fj-crud-show-controls
                    :config="config"
                    :create="isCreate"
                    :title="config.names.singular"
                >
                    <div
                        slot="controls"
                        class="pt-1"
                        v-if="controls.length > 0"
                    >
                        <hr />
                        <components
                            v-for="(component, key) in controls"
                            :key="key"
                            :is="component"
                            :nearItems="nearItems"
                            v-if="model"
                            :model="model"
                        />
                    </div>
                </fj-crud-show-controls>
            </b-col>
        </b-row>
    </fj-base-container>
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
        async loadModel() {
            await this.$store.dispatch('loadModel', {
                route: this.crud.model.route,
                id: this.id
            });
            this.$store.commit('FLUSH_SAVINGS');
            this.$bus.$emit('modelLoaded');
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
            //elements[0].scrollIntoView();
            let element = elements[0];
            let pos = element.style.position;
            let top = element.style.top;
            element.style.position = 'relative';
            element.style.top = '-30px';
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            element.style.top = top;
            element.style.position = pos;
            //console.log(docs);
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
        //this.crud.model = this.crud.models.model;
    },
    mounted() {
        this.scrollToFormFieldFromHash();
    }
};
</script>
