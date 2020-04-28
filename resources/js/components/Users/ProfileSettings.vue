<template>
    <fj-base-container size="lg">
        <fj-base-header title="Konto Einstellungen" />

        <b-row>
            <components
                v-for="(component, key) in config.form.components"
                :key="key"
                :is="component.name"
                v-bind="component.props"
                :model="user"
                :config="config"
            />
        </b-row>
    </fj-base-container>
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
            user: {}
        };
    },
    beforeMount() {
        this.user = this.crud(this.model);
    }
};
</script>
