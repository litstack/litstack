<template>
    <fj-container>
        <fj-header
            :title="formConfig.names.title.singular"
            :back="formConfig.back_route"
            :back-text="formConfig.back_text">

            <div
                slot="navigation"
                class="indent sm"
                v-if="nearItems">

                <b-button
                    :href="`${baseURL}${formConfig.names.table}/${nearItems.previous}/edit`"
                    :disabled="!nearItems.previous"
                    variant="transparent"
                    size="sm">
                    <fa-icon icon="arrow-left"/>
                </b-button>
                <b-button
                    :href="`${baseURL}${formConfig.names.table}/${nearItems.next}/edit`"
                    :disabled="!nearItems.next"
                    variant="transparent"
                    size="sm">
                    <fa-icon icon="arrow-right"/>
                </b-button>

            </div>

            <div slot="actions" class="indent sm">

                <components
                    v-for="(component, key) in actions"
                    :key="key"
                    :is="component"
                    :formConfig="formConfig"
                    :model="model"/>

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
                        <!--
                        <b-card
                            v-for="(ids, key) in formConfig.layout"
                            :key="key"
                            class="mb-4">
                            <fj-form
                                :ids="ids"
                                :model="model"/>
                        </b-card>
                        -->

                </b-row>
            </b-col>

            <fj-controlls/>
        </b-row>
    </fj-container>
</template>

<script>
import { mapGetters } from 'vuex'

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
                return []
            }
        },
        content: {
            type: Array,
            default: () => {
                return []
            }
        }
    },
    data() {
        return {
            model: null
        };
    },
    computed: {
        ...mapGetters(['baseURL'])
    },
    methods: {
        saved() {
            if (window.location.pathname.split('/').pop() == 'create') {
                setTimeout(() => {
                    window.location.replace(`${this.model.id}/edit`);
                }, 1)
            }
        }
    },
    beforeMount() {
        this.model = this.models.model;

        this.$bus.$on('modelsSaved', this.saved);
    }
};
</script>

<style lang="scss">
.fj-site-nav-actions{

}
</style>
