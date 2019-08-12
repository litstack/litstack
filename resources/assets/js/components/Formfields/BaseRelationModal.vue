<template>
    <b-modal
        :id="`${model.route}-form-relation-table-${field.id}`"
        size="lg"
        hide-footer
        :busy="true"
        :title="field.title">

        <fj-form-relation-table
            :items="items"
            :field="field"
            :select="true"
            :busy="busy"
            @selected="selected">

        </fj-form-relation-table>

    </b-modal>
</template>

<script>
import TranslatableEloquent from './../../eloquent/translatable'

export default {
    name: 'FormRelationModal',
    props: {
        field: {
            type: Object,
            required: true
        },
        model: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            busy: true,
            items: []
        }
    },
    mounted() {
        this.loadRelations()
    },
    methods: {
        selected(item) {
            this.$emit('selected', item)
        },
        async loadRelations() {
            this.busy = true
            await this._loadRelations()
            this.busy = false
        },
        async _loadRelations() {
            let payload = {
                model_type: this.model.model,
                model_id: this.model.id,
                id: this.field.id,
            }
            let response = await axios.post('/admin/relations/', payload)

            let items = []
            for(let i=0;i<response.data.length;i++) {
                items.push(new TranslatableEloquent(response.data[i]))
            }
            this.items = items
        }
    },

}
</script>

<style lang="css">

</style>
