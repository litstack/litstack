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

                <fj-form-block-add-buttons
                    :field="field"
                    :model="model"
                    :sortableRepeatables="sortableRepeatables"
                    @newBlock="newBlock"
                />
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
        newBlock(block) {
            this.sortableRepeatables.push(block);
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
