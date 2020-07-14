<template>
    <fj-container :fluid="page.expand ? 'fluid' : 'lg'">
        <fj-navigation
            :back="goBack.route || false"
            :back-text="goBack.text || ''"
        >
            <template :slot="slot" v-for="(part, slot) in page.navigation">
                <fj-base-component
                    v-for="(component, key) in part.components"
                    :component="component"
                    :key="key"
                    v-bind="{ ...page.props, ...$attrs }"
                />
            </template>
        </fj-navigation>
        <fj-header>
            <h3
                class="d-flex justify-content-between align-items-baseline"
                v-html="page.header.title"
            />
            <template slot="actions">
                <fj-base-component
                    v-for="(component, key) in page.header.left.components"
                    v-bind="{ ...page.props, ...$attrs }"
                    :component="component"
                    :key="key"
                />
            </template>
            <template slot="actions-right">
                <fj-base-component
                    v-for="(component, key) in page.header.right.components"
                    v-bind="{ ...page.props, ...$attrs }"
                    :component="component"
                    :key="key"
                />
            </template>
        </fj-header>
        <b-row>
            <fj-col :width="12">
                <b-row>
                    <fj-base-component
                        v-for="(component, key) in page.components"
                        v-bind="{ ...page.props, ...$attrs }"
                        :component="component"
                        :key="key"
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
