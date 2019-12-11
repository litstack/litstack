<template>
    <fj-container>
        <fj-header :title="'Dashboard'"></fj-header>
        <b-row>
            <component
                v-for="(component, index) in components"
                :is="component"
                :key="index"
            />
        </b-row>
    </fj-container>
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
