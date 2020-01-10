<template>
    <fj-container>
        <fj-header
            :title="formConfig.names.title.singular"
            :back="formConfig.back_route"
            :back-text="formConfig.back_text"
        >
            <crud-show-near-items
                slot="navigation"
                v-if="nearItems"
                :formConfig="formConfig"
                :nearItems="nearItems"
            />

            <div slot="actions" class="pt-3" v-if="actions.length > 0">
                <components
                    v-for="(component, key) in actions"
                    :key="key"
                    :is="component"
                    :formConfig="formConfig"
                    :model="model"
                />
            </div>
        </fj-header>

        <b-row>
            <b-col cols="12" md="9" order-md="1">
                <b-row class="fjord-form">
                    <component
                        v-for="(component, key) in content"
                        :key="key"
                        :is="component"
                        :formConfig="formConfig"
                        :model="model"
                    />
                </b-row>
            </b-col>

            <fj-controls>
                <!-- Custom controls -->
            </fj-controls>
        </b-row>
    </fj-container>
</template>

<script>
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
        content: {
            type: Array,
            default: () => {
                return [];
            }
        }
    },
    data() {
        return {
            model: null
        };
    },
    methods: {
        saved() {
            if (window.location.pathname.split('/').pop() == 'create') {
                setTimeout(() => {
                    window.location.replace(`${this.model.id}/edit`);
                }, 1);
            }
        }
    },
    beforeMount() {
        this.model = this.models.model;

        this.$bus.$on('modelsSaved', this.saved);
    }
};
</script>
