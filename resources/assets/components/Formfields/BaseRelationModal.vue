<template>
    <b-modal
        :id="`${model.route}-form-relation-table-${field.id}-${model.id}`"
        size="lg"
        hide-footer
        :busy="true"
        :title="field.title"
    >
        <b-input-group class="mb-3">
            <b-input-group-prepend is-text>
                <fa-icon icon="search" />
            </b-input-group-prepend>

            <b-form-input :placeholder="`Filter`" v-model="search" />

            <b-input-group-append v-if="hasEditLink">
                <b-link
                    :href="`${baseURL}${field.edit}/create`"
                    class="btn btn-primary"
                >
                    <i class="fas fa-plus"></i>
                    {{ field.model.split('\\').pop() }}
                </b-link>
            </b-input-group-append>
        </b-input-group>
        <b-table-simple outlined hover id="relations">
            <fj-colgroup :icons="['check']" :cols="cols" />

            <tbody>
                <b-tr
                    v-for="(item, key) in items"
                    :key="key"
                    style="cursor:pointer;"
                    @click="selected(item)"
                >
                    <b-td
                        style="vertical-align: middle;"
                        v-for="(col, ckey) in cols"
                        :key="ckey"
                        :class="
                            col.key == 'drag' ? 'fjord-draggable__dragbar' : ''
                        "
                    >
                        <div
                            class="custom-control custom-radio"
                            v-if="col.key == 'check' && !hasMany"
                        >
                            <input
                                type="radio"
                                autocomplete="off"
                                class="custom-control-input"
                                value=""
                                :checked="itemChecked(item)"
                            />
                            <label class="custom-control-label"></label>
                        </div>
                        <b-checkbox
                            v-else-if="col.key == 'check' && hasMany"
                            :checked="itemChecked(item)"
                        />
                        <fj-table-col v-else :item="item" :col="col" />
                    </b-td>
                </b-tr>
            </tbody>
        </b-table-simple>
    </b-modal>
</template>

<script>
import TranslatableEloquent from './../../eloquent/translatable';
import TableModel from './../../eloquent/table.model';
import { mapGetters } from 'vuex';

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
        },
        hasMany: {
            type: Boolean,
            default: true
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
            busy: true,
            cols: [],
            items: [],
            search: null
        };
    },
    beforeMount() {
        this.cols.push({ key: 'check' });

        for (let i = 0; i < this.field.preview.length; i++) {
            let col = this.field.preview[i];

            if (typeof col == typeof '') {
                col = { key: col };
            }
            this.cols.push(col);
        }
    },
    mounted() {
        this.loadRelations();
    },
    methods: {
        itemChecked(item) {
            return this.selectedModels.find(model =>
                model ? model.id == item.id : false
            )
                ? true
                : false;
        },
        selected(item) {
            if (this.itemChecked(item) && this.hasMany) {
                this.$emit('remove', item.id);
            } else {
                this.$emit('selected', item);
            }
        },
        hasEditLink() {
            return this.field.edit != undefined;
        },
        addLink() {
            return `${this.baseURL}${this.field.edit}/create`;
        },
        async loadRelations() {
            this.busy = true;
            await this._loadRelations();
            this.busy = false;
        },
        async _loadRelations() {
            let payload = {
                model_type: this.model.model,
                model_id: this.model.id,
                id: this.field.id
            };

            let response = await axios.post('relations/', payload);

            let items = [];
            for (let i = 0; i < response.data.length; i++) {
                items.push(new TableModel(response.data[i]));
            }
            this.items = items;
        }
    },
    watch: {
        search(needle) {
            $('#relations tbody tr').each(function() {
                let row = $(this);
                let haystack = row.text();

                if (!haystack.search(needle)) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }
    },
    computed: {
        ...mapGetters(['baseURL'])
    }
};
</script>

<style lang="css"></style>
