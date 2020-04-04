<template>
    <fj-base-container>
        <fj-base-header
            :title="formConfig.names.title.singular"
            :back="formConfig.back_route"
            :back-text="formConfig.back_text"
        >
            <fj-crud-show-near-items
                slot="navigation"
                v-if="nearItems"
                :formConfig="formConfig"
                :nearItems="nearItems"
            />

            <div slot="actions" class="pt-3" v-if="actions.length > 0">
                <components
                    v-for="(component, key) in headerComponents"
                    :key="key"
                    :is="component"
                    :formConfig="formConfig"
                    v-if="crud.model"
                    :model="crud.model"
                />
            </div>
        </fj-base-header>
        <b-row>
            <b-col cols="12" md="9" order-md="1">
                <b-row class="fjord-form">
                    <component
                        v-for="(component, key) in content"
                        :key="key"
                        :is="component"
                        :formConfig="formConfig"
                        v-if="crud.model"
                        :model="crud.model"
                    />
                </b-row>
            </b-col>

            <b-col cols="12" md="3" order-md="2" class="pb-4 mb-md-0">
                <fj-crud-show-controls
                    :create="create"
                    :title="formConfig.names.title.singular"
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
                            :formConfig="formConfig"
                            v-if="crud.model"
                            :model="crud.model"
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
        models: {
            type: Object
        },
        formConfig: {
            type: [Array, Object],
            required: true
        },
        nearItems: {
            type: Object
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
            id: this.models.model.id,
            route: null
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
        }
    },
    computed: {
        ...mapGetters(['crud', 'form']),
        create() {
            return window.location.pathname.split('/').pop() == 'create';
        }
    },
    beforeMount() {
        //this.crud.model = this.crud.models.model;

        this.$store.commit('SET_MODEL', this.models.model);

        this.$store.dispatch('setFormConfig', this.formConfig);

        this.$bus.$on('modelsSaved', () => {
            this.saved();
        });

        this.$bus.$on('loadModel', () => {
            this.loadModel();
        });

        this.$bus.$on('modelLoaded', () => {
            this.$bvToast.toast(
                this.$t('fj.model_loaded', {
                    model: this.form.config.names.title.singular
                }),
                {
                    variant: 'success'
                }
            );
        });
    }
};
</script>
