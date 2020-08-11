<template>
    <div class="form-control-expand">
        <b-alert
            show
            variant="warning"
            v-html="
                __('fj.not_created', {
                    relation: field.title,
                    model: 'Item'
                })
            "
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'FieldAlertNotCreated',
    props: {
        field: {
            type: Object,
            required: true
        }
    }
};
</script>
