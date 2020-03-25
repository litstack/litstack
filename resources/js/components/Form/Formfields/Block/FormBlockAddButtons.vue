<template>
    <div>
        <b-button
            class="mr-2"
            size="sm"
            v-for="(repeatables, type) in field.repeatables"
            :key="type"
            @click.prevent="add(type)"
            ><fa-icon icon="plus" /> add {{ type }}</b-button
        >
    </div>
</template>

<script>
import FjordModel from '@fj-js/eloquent/fjord.model';
export default {
    name: 'FormBlockAddButtons',
    props: {
        field: {
            type: Object,
            required: true
        },
        model: {
            type: Object,
            required: true
        },
        sortableRepeatables: {
            type: Array,
            required: true
        }
    },
    methods: {
        async add(type) {
            let data = {
                type: type,
                model_type: this.model ? this.model.model : '',
                model_id: this.model ? this.model.id : '',
                field_id: this.field.id,
                value: this.newBlock(type),
                order_column: this.sortableRepeatables.length
            };

            let response = await axios.post(`form_blocks`, data);
            let model = new FjordModel(response.data);

            this.$emit('newBlock', model);

            this.$bvToast.toast(this.$t('fj.new_block', { type }), {
                variant: 'success'
            });
        },
        newBlock(type) {
            let obj = {};

            for (var i = 0; i < this.field.repeatables[type].length; i++) {
                if (this.field.repeatables[type][i].id != 'image') {
                    obj[this.field.repeatables[type][i].id] = '';
                }
            }

            return obj;
        }
    }
};
</script>
