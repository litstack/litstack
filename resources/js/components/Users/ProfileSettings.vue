<template>
    <fj-container size="lg">
        <fj-navigation />
        <fj-header
            :title="
                __('base.item_settings', {
                    item: __('base.profile').capitalize()
                })
            "
        />

        <b-row>
            <components
                v-for="(component, key) in config.form.components"
                :key="key"
                :is="component.name"
                v-bind="component.props"
                :model="profile"
                :config="config"
            />
        </b-row>
    </fj-container>
</template>

<script>
export default {
    name: 'ProfileSettings',
    props: {
        model: {
            required: true,
            type: Object
        },
        config: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            profile: {}
        };
    },
    beforeMount() {
        this.profile = this.crud(this.model);
    }
};
</script>
