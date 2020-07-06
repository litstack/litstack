<template>
    <fj-container :fluid="page.expand ? 'fluid' : 'lg'">
        <fj-navigation
            :back="goBack.route || false"
            :back-text="goBack.text || ''"
        >
            <template slot="left">
                <components
                    v-for="(component, key) in page.navigation.left.components"
                    :key="key"
                    :is="component.name"
                    v-bind="{ ...component.props, ...page.props, ...$attrs }"
                />
            </template>
            <template slot="right">
                <components
                    v-for="(component, key) in page.navigation.right.components"
                    :key="key"
                    :is="component.name"
                    v-bind="{ ...component.props, ...page.props, ...$attrs }"
                />
            </template>
        </fj-navigation>
        <fj-header>
            <h3
                class="d-flex justify-content-between align-items-baseline"
                v-html="page.header.title"
            />
            <template slot="actions">
                <components
                    v-for="(component, key) in page.header.left.components"
                    :key="key"
                    :is="component.name"
                    v-bind="{ ...component.props, ...page.props, ...$attrs }"
                />
            </template>
            <template slot="actions-right">
                <components
                    v-for="(component, key) in page.header.right.components"
                    :key="key"
                    :is="component.name"
                    v-bind="{ ...component.props, ...page.props, ...$attrs }"
                />
            </template>
        </fj-header>
        <b-row>
            <fj-col :width="12">
                <b-row>
                    <components
                        v-for="(component, key) in page.components"
                        :key="key"
                        :is="component.name"
                        v-bind="{
                            ...component.props,
                            ...page.props,
                            ...$attrs
                        }"
                    />
                </b-row>
            </fj-col>
        </b-row>
    </fj-container>
</template>

<script>
export default {
    name: 'Page',
    props: {
        page: {
            type: Object,
            require: true
        }
    },
    computed: {
        goBack() {
            if (!this.page.back) {
                return {};
            }

            return this.page.back;
        }
    }
};
</script>
