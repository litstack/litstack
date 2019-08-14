<template>
    <fj-form-item :field="field">
        <div class="fjord-block card no-fx">
            <div class="card-body">
                <draggable
                    v-model="sortableRepeatables"
                    @end="newOrder"
                    handle=".fjord-draggable__dragbar">

                    <div
                        class="fjord-draggable"
                        v-for="(repeatable, index) in sortableRepeatables"
                        :key="repeatable.id">

                        <div class="fjord-draggable__dragbar d-flex justify-content-center">
                            <i class="fas fa-grip-horizontal text-muted"></i>
                        </div>

                        <fj-form :model="repeatable" />

                        <b-row>
                            <b-col sm="12" class="text-center fj-trash text-muted">
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
        },
    },
    data() {
        return {
            sortableRepeatables: []
        };
    },
    beforeMount() {
        if(!this.repeatables) {
            return
        }
        this.sortableRepeatables = this.repeatables.items.items
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

            let response = await axios.post(`repeatables`, data);
            let model = new Eloquent(response.data);

            this.sortableRepeatables.push(model)
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
            this.sortableRepeatables.splice(this.sortableRepeatables.indexOf(repeatable), 1)

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
                order: this.sortableRepeatables.map(item => item.id)
            };

            await axios.put('order', payload)

            this.$notify({
                group: 'general',
                type: 'aw-success',
                title: this.field.title,
                text: 'Changed order.',
                duration: 1500
            });
        }
    }
};
</script>

<style lang="scss">

</style>
