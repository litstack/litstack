<template>
    <b-col :cols="field.width">
        <div class="fjord-draggable">
            <div class="fjord-draggable__dragbar d-flex justify-content-center">
                <i class="fas fa-grip-horizontal text-muted"></i>
            </div>

            <fj-fjord-form :model="repeatable" />

            <b-row>
                <b-col sm="12" class="text-center fj-trash text-muted">
                    <fa-icon
                        icon="trash"
                        @click="deleteRepeatable(repeatable)"
                    />
                </b-col>
            </b-row>
        </div>
    </b-col>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FormBlockItem',
    props: {
        model: {
            type: Object,
            required: true
        },
        repeatable: {
            type: Object,
            required: true
        },
        field: {
            type: Object,
            required: true
        }
    },
    computed: {
        ...mapGetters(['form'])
    },
    methods: {
        async deleteRepeatable(repeatable) {
            let response = await axios.delete(
                `${this.form.config.route}/${this.model.id}/blocks/${repeatable.id}`
            );
            this.$emit('deleteBlock', repeatable);
        }
    }
};
</script>
