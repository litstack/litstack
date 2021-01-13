<template>
    <lit-container :fluid="page.expand ? 'fluid' : 'lg'">
        <lit-navigation
            :back="goBack.route || false"
            :back-text="goBack.text || ''"
            :breadcrumb="page.breadcrumb || {}"
            :current="page.header.title"
        >
            <template :slot="slot" v-for="(part, slot) in page.navigation">
                <lit-base-component
                    v-for="(component, key) in part.components"
                    :component="component"
                    :key="`${slot}-${key}`"
                    v-bind="{ ...page.props, ...$attrs }"
                />
            </template>
        </lit-navigation>
        <lit-header>
            <h3
                class="d-flex justify-content-between align-items-baseline"
                v-html="page.header.title"
            />
            <template slot="actions">
                <lit-base-component
                    v-for="(component, key) in page.header.left.components"
                    v-bind="{ ...page.props, ...$attrs }"
                    :component="component"
                    :key="`header-left-${key}`"
                />
            </template>
            <template slot="actions-right">
                <lit-base-component
                    v-for="(component, key) in page.header.right.components"
                    v-bind="{ ...page.props, ...$attrs }"
                    :component="component"
                    :key="`header-right-${key}`"
                />
            </template>
        </lit-header>
        <b-row>
            <lit-col :width="12">
                <b-row>
                    <lit-base-component
                        v-for="(component, key) in page.components"
                        v-bind="{ ...page.props, ...$attrs }"
                        :component="component"
                        :key="key"
                    />
                </b-row>
            </lit-col>
        </b-row>
    </lit-container>
</template>

<script>
export default {
    name: 'Page',
    props: {
        page: {
            type: Object,
            require: true,
        },
    },
    computed: {
        goBack() {
            if (!this.page.back) {
                return {};
            }

            return this.page.back;
        },
    },
};
</script>
