<template>
    <fj-form-item :field="field" :model="model">
        <div class="form-control-expand" v-if="model.id">
            <div v-if="busy" class="d-flex justify-content-around">
                <fj-spinner />
            </div>
            <draggable
                v-model="sortableBlocks"
                @end="newOrder"
                handle=".fj-draggable__dragbar"
                tag="b-row"
                :class="{ 'mb-0': field.readonly }"
                v-else-if="sortableBlocks.length > 0"
            >
                <fj-field-blocks-item
                    v-for="(block, index) in sortableBlocks"
                    :key="index"
                    :block="block"
                    :field="field"
                    :model="model"
                    @deleteBlock="deleteBlock"
                />
            </draggable>

            <div v-else>
                <fj-field-alert-empty
                    :field="field"
                    :class="{ 'mb-0': field.readonly }"
                />
            </div>

            <fj-field-blocks-add-buttons
                v-if="!field.readonly"
                :field="field"
                :model="model"
                :sortableBlocks="sortableBlocks"
                @newBlock="newBlock"
            />
        </div>
        <template v-else>
            <fj-field-alert-not-created :field="field" class="mb-0" />
        </template>
    </fj-form-item>
</template>

<script>
export default {
    name: 'FieldBlocks',
    props: {
        field: {
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
            sortableBlocks: [],
            busy: true
        };
    },
    beforeMount() {
        this.loadBlocks();
    },
    methods: {
        async loadBlocks() {
            this.busy = true;
            let response = await axios.get(
                `${this.field.route_prefix}/blocks/${this.field.id}`
            );
            this.sortableBlocks = [];
            for (let i in response.data) {
                let block = response.data[i];
                this.newBlock(block);
            }
            this.busy = false;
        },
        newBlock(block) {
            this.sortableBlocks.push(this.crud(block));
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
                    `${this.field.route_prefix}/${this.model.id}/${this.field.id}/order`,
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
    }
};
</script>
