<template>
    <fj-form-item :field="field" :model="model">
        <div class="form-control-expand" v-if="model.id">
            <draggable
                v-model="sortableBlocks"
                @end="newOrder"
                handle=".fjord-draggable__dragbar"
                tag="b-row"
                :class="{ 'mb-0': readonly }"
                v-if="sortableBlocks.length > 0"
            >
                <fj-form-blocks-item
                    v-for="(block, index) in sortableBlocks"
                    :key="index"
                    :block="block"
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

            <fj-form-blocks-add-buttons
                v-if="!readonly"
                :field="field"
                :model="model"
                :sortableBlocks="sortableBlocks"
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
    name: 'FormBlocks',
    props: {
        field: {
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
            sortableBlocks: []
        };
    },
    beforeMount() {
        this.sortableBlocks = this.model[this.field.id].items.items;
    },
    methods: {
        newBlock(block) {
            this.sortableBlocks.push(block);
        },
        deleteBlock(block) {
            this.sortableBlocks.splice(this.sortableBlocks.indexOf(block), 1);

            this.$bvToast.toast(this.$t('fj.deleted_block'), {
                variant: 'success'
            });
        },
        async newOrder() {
            let payload = {
                ids: this.sortableBlocks.map(item => item.id)
            };
            try {
                let response = await axios.put(
                    `${this.form.config.route_prefix}/${this.model.id}/${this.field.id}/order`,
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
