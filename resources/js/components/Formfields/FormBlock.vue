<template>
    <fj-form-item :field="field" :model="model">
        <div class="fjord-block card no-fx" v-if="model.id">
            <div class="card-body">
                <draggable
                    v-model="sortableRepeatables"
                    @end="newOrder"
                    handle=".fjord-draggable__dragbar"
                    tag="b-row"
                >
                    <b-col
                        :cols="field.block_width"
                        v-for="(repeatable, index) in sortableRepeatables"
                        :key="repeatable.id"
                    >
                        <div class="fjord-draggable">
                            <div
                                class="fjord-draggable__dragbar d-flex justify-content-center"
                            >
                                <i
                                    class="fas fa-grip-horizontal text-muted"
                                ></i>
                            </div>

                            <fj-fjord-form :model="repeatable" />

                            <b-row>
                                <b-col
                                    sm="12"
                                    class="text-center fj-trash text-muted"
                                >
                                    <fa-icon
                                        icon="trash"
                                        @click="deleteRepeatable(repeatable)"
                                    />
                                </b-col>
                            </b-row>
                        </div>
                    </b-col>
                </draggable>

                <button
                    class="btn btn-secondary btn-sm mr-2"
                    v-for="(repeatables, type) in field.repeatables"
                    @click.prevent="add(type)"
                >
                    <fa-icon icon="plus" /> add {{ type }}
                </button>
            </div>
        </div>
        <template v-else>
            <b-alert show variant="warning"
                >{{ form.config.names.title.singular }} has to be created in
                order to add <i>{{ field.title }}</i></b-alert
            >
        </template>
    </fj-form-item>
</template>

<script>
import FjordModel from '@fj-js/eloquent/fjord.model';
import { mapGetters } from 'vuex';

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
            sortableRepeatables: []
        };
    },
    beforeMount() {
        if (!this.repeatables) {
            return;
        }
        this.sortableRepeatables = this.repeatables.items.items;
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

            this.sortableRepeatables.push(model);

            this.$bvToast.toast(this.$t('new_block', { type }), {
                variant: 'success'
            });
        },
        async deleteRepeatable(repeatable) {
            await repeatable.delete();
            this.sortableRepeatables.splice(
                this.sortableRepeatables.indexOf(repeatable),
                1
            );

            this.$bvToast.toast(this.$t('deleted_block'), {
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
        },
        async newOrder() {
            let payload = {
                model: 'AwStudio\\Fjord\\Form\\Database\\FormBlock',
                order: this.sortableRepeatables.map(item => item.id)
            };

            await axios.put('order', payload);

            this.$bvToast.toast(this.$t('order_changed'), {
                variant: 'success'
            });
        }
    },
    computed: {
        ...mapGetters(['form'])
    }
};
</script>
