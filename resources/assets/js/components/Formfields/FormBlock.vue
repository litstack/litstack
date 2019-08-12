<template>
    <fj-form-item :field="field">
        <div class="fjord-block card no-fx">
            <div class="card-body">
                <draggable
                    v-model="repeatables.items.items"
                    @end="newOrder"
                    handle=".fjord-draggable__dragbar">

                    <div
                        class="fjord-draggable"
                        v-for="(repeatable, index) in repeatables.items.items"
                        :key="repeatable.id">

                        <div class="fjord-draggable__dragbar d-flex justify-content-center">
                            <i class="fas fa-grip-horizontal text-muted"></i>
                        </div>

                        <fj-form :model="repeatable" />

                        <b-row>
                            <b-col sm="12" class="text-center fj-block-trash text-muted">
                                <fa-icon icon="trash" @click="deleteRepeatable(repeatable)"/>
                            </b-col>
                        </b-row>

                    </div>

                </draggable>

                <button
                    class="btn btn-secondary btn-sm mr-2"
                    v-for="(repeatables, type) in field.repeatables"
                    @click.prevent="add(type)">

                    <fa-icon icon="plus"/> add {{ type }}

                </button>
            </div>
        </div>
    </fj-form-item>
</template>

<script>
import Eloquent from './../../eloquent'

export default {
    name: 'FormBlock',
    props: {
        field: {
            type: Object
        },
        repeatables: {
            type: Object
        },
        model: {
            type: Object
        },
        pageName: {
            type: String
        }
    },
    data() {
        return {
            block: null,
            payload: []
        };
    },
    watch: {
        payload(val) {
            this.$emit('input', val);
        }
    },
    methods: {
        async add(type) {
            let data = {
                type: type,
                page_name: this.pageName,
                model_type: this.model ? this.model.model : '',
                model_id: this.model ? this.model.id : '',
                block_name: this.field.id,
                content: this.newRepeatable(type)
            };

            let response = await axios.post(`/admin/repeatables`, data);
            let model = new Eloquent(response.data);
            this.$emit('newRepeatable', model);
            this.$forceUpdate();
            this.$notify({
                group: 'general',
                type: 'aw-success',
                title: this.field.title,
                text: `Added new ${type} block.`,
                duration: 1500
            });
        },
        async deleteRepeatable(repeatable) {
            await repeatable.delete()
            this.$forceUpdate()
            this.$notify({
                group: 'general',
                type: 'aw-success',
                title: this.field.title,
                text: 'Deleted block.',
                duration: 1500
            });
        },
        newRepeatable(type) {
            let obj = {};

            for (var i = 0; i < this.field.repeatables[type].length; i++) {
                if (this.field.repeatables[type][i].id != 'image') {
                    obj[this.field.repeatables[type][i].id] = '';
                }
            }

            return obj;
        },
        async newOrder() {
            let payload = {
                model: 'AwStudio\\Fjord\\Models\\Repeatable',
                order: this.repeatables.items.map(item => item.id).toArray()
            };

            let response = await axios.put('/admin/order', payload)
            console.log('Response: ', response.data);
        }
    }
};
</script>

<style lang="scss">
.fj-block-trash {
    svg:hover{
        cursor: pointer;
        color: black;
    }
}
</style>
