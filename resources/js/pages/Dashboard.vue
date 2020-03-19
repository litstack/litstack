<template>
    <fj-base-container>
        <fj-base-header :title="'Dashboard'"></fj-base-header>
        <b-row>
            <component
                v-for="(component, index) in components"
                :is="component"
                :key="index"
            />
        </b-row>
    </fj-base-container>
</template>

<script>
export default {
    name: 'Dashboard',
    props: {
        components: {
            type: Array
        }
    }
};
</script>
