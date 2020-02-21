<template>
    <div class="input-group-append" v-if="field.translatable">
        <span class="input-group-text">
            {{ language }}
        </span>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'FormLanguage',
    props: {
        field: {
            type: Object,
            required: true
        }
    },
    computed: {
        ...mapGetters(['language'])
    }
};
</script>

<style lang="css"></style>
