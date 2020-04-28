<template>
    <b-row>
        <b-col>
            <div class="fjord-draggable">
                EDIT
            </div>
        </b-col>
    </b-row>
</template>

<script>
export default {
    name: 'FieldRelationEdit',
    props: {
        model: {
            type: Object
        },
        modelId: {
            required: true
        },
        items: {
            required: true,
            type: Object
        },
        field: {
            required: true,
            type: Object
        }
    }
};
</script>
