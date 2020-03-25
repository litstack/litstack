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
                    <fj-form-block-item
                        v-for="(repeatable, index) in sortableRepeatables"
                        :key="repeatable.id"
                        :repeatable="repeatable"
                        :field="field"
                        @deleteBlock="deleteBlock"
                    />
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
        if (!!this.repeatables) {
            this.sortableRepeatables = this.repeatables.items.items;
        }
    },
    methods: {
        newBlock(block) {
            this.sortableRepeatables.push(block);
        },
        deleteBlock(repeatable) {
            this.sortableRepeatables.splice(
                this.sortableRepeatables.indexOf(repeatable),
                1
            );

            this.$bvToast.toast(this.$t('fj.deleted_block'), {
                variant: 'success'
            });
        },
        async newOrder() {
            let payload = {
                model: 'AwStudio\\Fjord\\Form\\Database\\FormBlock',
                order: this.sortableRepeatables.map(item => item.id)
            };

            await axios.put('order', payload);

            this.$bvToast.toast(this.$t('fj.order_changed'), {
                variant: 'success'
            });
        }
    },
    computed: {
        ...mapGetters(['form'])
    }
};
</script>
