<template>
    <fj-form-item :field="field" :model="model">
        <div class="form-control-expand" v-if="model.id">
            <draggable
                v-model="sortableRepeatables"
                @end="newOrder"
                handle=".fjord-draggable__dragbar"
                tag="b-row"
                :class="{ 'mb-0': readonly }"
                v-if="sortableRepeatables.length > 0"
            >
                <fj-form-block-item
                    v-for="(repeatable, index) in sortableRepeatables"
                    :key="repeatable.id"
                    :repeatable="repeatable"
                    :field="field"
                    :model="model"
                    :readonly="readonly"
                    @deleteBlock="deleteBlock"
                />
            </draggable>

            <div v-else>
                <fj-form-alert-empty
                    :field="field"
                    :class="{ 'mb-0': readonly }"
                />
            </div>

            <fj-form-block-add-buttons
                v-if="!readonly"
                :field="field"
                :model="model"
                :sortableRepeatables="sortableRepeatables"
                @newBlock="newBlock"
            />
        </div>
        <template v-else>
            <fj-form-alert-not-created :field="field" class="mb-0" />
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
        },
        readonly: {
            required: true,
            type: Boolean
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
                ids: this.sortableRepeatables.map(item => item.id)
            };
            try {
                let response = await axios.put(
                    `${this.form.config.route}/${this.model.id}/relation/${this.field.id}/order`,
                    payload
                );
            } catch (e) {
                console.log(e);
                return;
            }

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
