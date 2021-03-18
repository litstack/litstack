<template>
    <span>
        <lit-fa-icon icon="eye" />

        {{ __('crud.preview') }}

        <lit-page-preview :show="true" :routes="urls" />
    </span>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudPreview',
    props: {
        urls: {
            type: Object,
            required: true,
        },
    },
    mounted() {
        this.$nextTick(() => {
            this.$parent.$parent.$el.addEventListener('click', () => {
                this.$bvModal.show('lit-page-preview');
            });
        });
    },
};
</script>
