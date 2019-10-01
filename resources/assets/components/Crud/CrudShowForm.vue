<template>
    <b-col cols="12">
        <b-card
            v-for="(ids, key) in formConfig.layout"
            :key="key"
            class="mb-5"
            header-class="d-flex justify-content-between align-items-center"
        >
            <template v-slot:header v-if="formTitle(ids)">
                <b>
                    {{ formTitle(ids) }}
                </b>
                <button
                    v-b-toggle="`accordion-${key}`"
                    class="fjord-fjorm-collapse"
                >
                    <i class="fas fa-chevron-down"></i>
                </button>
            </template>
            <b-collapse :id="`accordion-${key}`" visible>
                <fj-form :ids="ids" :model="model" />
            </b-collapse>
        </b-card>
    </b-col>
</template>

<script>
export default {
    name: 'CrudShowForm',
    props: {
        formConfig: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        }
    },
    methods: {
        formTitle(ids) {
            let form_header = null;
            for (var i = 0; i < ids.length; i++) {
                form_header = ids[i].includes('form_header')
                    ? ids[i]
                    : form_header;
            }

            return form_header
                ? _.filter(this.model.items.items, [
                      'attributes.field_id',
                      form_header
                  ])[0].form_fields[0].title
                : null;
        }
    }
};
</script>
