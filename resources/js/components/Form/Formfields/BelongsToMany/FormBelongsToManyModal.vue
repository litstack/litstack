<template>
    <div>
        <b-button variant="secondary" size="sm" @click="visible = !visible">
            {{ $t('fj.edit') }}
        </b-button>
        <b-modal v-model="visible" hide-footer :title="title" size="lg">
            <b-input-group class="mb-3">
                <b-input-group-prepend is-text>
                    <fa-icon icon="search" />
                </b-input-group-prepend>

                <b-form-input
                    v-model="filter"
                    type="search"
                    placeholder="Type to Search"
                ></b-form-input>
            </b-input-group>

            <b-table
                :items="relations"
                :filter="filter"
                :fields="fields"
                outlined
                @row-clicked="selected"
            >
                <template v-slot:cell(selected)="data">
                    <input
                        type="checkbox"
                        autocomplete="off"
                        value=""
                        class="pointer-events-none"
                        :checked="itemChecked(data.item)"
                    />
                </template>
                <!-- checkbox column header -->
                <template v-slot:head(selected)="data">
                    <fa-icon icon="check-square" />
                </template>
                <!-- make the checkbox column narrow -->
                <template v-slot:table-colgroup="scope">
                    <col
                        v-for="field in scope.fields"
                        :key="field.key"
                        :style="{
                            width: field.key === 'selected' ? '36px' : 'auto'
                        }"
                    />
                </template>
                <template v-slot:cell()="data">
                    <fj-table-col :item="data.item" :col="data.field" />
                </template>
            </b-table>
        </b-modal>
    </div>
</template>

<script>
import TableModel from '@fj-js/eloquent/table.model';
import { mapGetters } from 'vuex';

export default {
    name: 'FormBelongsToManyModal',
    props: {
        field: {
            required: true,
            type: Object
        },
        selectedModels: {
            type: Array,
            default: () => {
                return [];
            }
        }
    },
    data() {
        return {
            visible: false,
            relations: [],
            items: [],
            filter: null,
            fields: ['selected']
        };
    },
    beforeMount() {
        this.fetchRelations();

        for (let i = 0; i < this.field.preview.length; i++) {
            let col = this.field.preview[i];
            if (typeof col == typeof '') {
                col = { key: col };
            }
            this.fields.push(col);
        }
    },
    methods: {
        async fetchRelations() {
            try {
                const { data } = await axios.post(`/belongs-to-many`, {
                    model: this.crud.model.model,
                    field: this.field
                });
                this.relations = data;
            } catch (e) {}
        },
        itemChecked(item) {
            return this.selectedModels.find(model =>
                model ? model.id == item.id : false
            )
                ? true
                : false;
        },
        selected(item) {
            this.$emit('toggle', item);
        }
    },
    computed: {
        ...mapGetters(['crud']),
        title() {
            return this.field.form
                ? this.field.form.names.plural
                : this.field.title;
        }
    }
};
</script>
