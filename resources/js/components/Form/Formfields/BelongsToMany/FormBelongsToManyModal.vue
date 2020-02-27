<template>
    <div>
        <b-button variant="secondary" size="sm" @click="visible = !visible">
            add
        </b-button>
        <b-modal v-model="visible" hide-footer :title="title">
            <b-table-simple outlined hover id="relations">
                <fj-colgroup :icons="['check']" :cols="cols" />

                <tbody>
                    <tr
                        v-for="(item, key) in relations"
                        :key="key"
                        style="cursor:pointer;"
                        @click="selected(item)"
                    >
                        <b-td
                            style="vertical-align: middle;"
                            v-for="(col, ckey) in cols"
                            :key="ckey"
                            :class="
                                col.key == 'drag'
                                    ? 'fjord-draggable__dragbar'
                                    : ''
                            "
                        >
                            <div
                                class="custom-control custom-radio"
                                v-if="col.key == 'check'"
                            >
                                <input
                                    type="radio"
                                    autocomplete="off"
                                    class="custom-control-input pointer-events-none"
                                    value=""
                                    :checked="itemChecked(item)"
                                />
                                <label class="custom-control-label"></label>
                            </div>
                            <b-checkbox
                                class="pointer-events-none"
                                v-else-if="col.key == 'check'"
                                :checked="itemChecked(item)"
                            />
                            <fj-table-col v-else :item="item" :col="col" />
                        </b-td>
                    </tr>
                </tbody>
            </b-table-simple>
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
            cols: [],
            items: []
        };
    },
    beforeMount() {
        this.fetchRelations();

        this.cols.push({ key: 'check' });

        for (let i = 0; i < this.field.preview.length; i++) {
            let col = this.field.preview[i];

            if (typeof col == typeof '') {
                col = { key: col };
            }
            this.cols.push(col);
        }
    },
    methods: {
        async fetchRelations() {
            try {
                const { data } = await axios.post(`/belongs-to-many`, {
                    model: this.field.model
                });
                this.items = data;
                let items = [];
                for (let i = 0; i < data.length; i++) {
                    items.push(new TableModel(data[i]));
                }
                this.relations = items;
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
