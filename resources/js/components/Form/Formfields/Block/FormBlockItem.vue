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
export default {
    name: 'FormBlockItem',
    props: {
        repeatable: {
            type: Object,
            required: true
        },
        field: {
            type: Object,
            required: true
        }
    },
    methods: {
        async deleteRepeatable(repeatable) {
            await repeatable.delete();
            this.$emit('deleteBlock', repeatable);
        }
    }
};
</script>
