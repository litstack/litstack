<template>
    <fj-container :fluid="config.index.expand ? 'fluid' : 'lg'">
        <fj-navigation :controls="slots.navControls">
            <b-button
                size="md"
                variant="primary"
                slot="right"
                :href="`${baseURL}${config.route_prefix}/create`"
            >
                <fa-icon icon="plus" />
                {{ $t('fj.add_model', { model: config.names.singular }) }}
            </b-button>
        </fj-navigation>
        <fj-header :title="config.names.plural">
            <div slot="actions" class="d-flex align-items-center">
                <b-button
                    v-for="(button, key) in config.index.slots[
                        'header-actions'
                    ]"
                    :key="key"
                    variant="transparent"
                    class="mr-2"
                >
                    {{ button }}
                </b-button>
            </div>
        </fj-header>

        <b-row>
            <component
                v-for="(component, key) in config.index.components"
                :key="key"
                :is="component.name"
                v-bind="component.props"
                :slots="component.slots"
                :config="config"
            />
        </b-row>
    </fj-container>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudIndex',
    props: {
        config: {
            required: true,
            type: Object
        },
        slots: {
            required: true,
            type: Object
        }
    },

    beforeMount() {
        this.$store.commit('SET_CONFIG', this.config);
    },
    computed: {
        ...mapGetters(['baseURL'])
    },
    methods: {}
};
</script>
