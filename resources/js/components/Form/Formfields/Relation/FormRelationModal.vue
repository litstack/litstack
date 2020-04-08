<template>
    <b-modal
        :id="`${model.route}-form-relation-table-${field.id}-${model.id}`"
        size="lg"
        hide-footer
        :busy="true"
        :title="title"
    >
        <b-tabs
            :content-class="models.length > 1 ? 'mt-3' : ''"
            :nav-class="models.length > 1 ? '' : 'hide'"
        >
            <b-tab
                v-for="(m, key) in models"
                :key="key"
                :title="m"
                v-bind:active="currentModel == m"
            >
                <b-input-group class="mb-3">
                    <b-input-group-prepend is-text>
                        <fa-icon icon="search" />
                    </b-input-group-prepend>

                    <b-form-input :placeholder="`Filter`" v-model="search" />

                    <b-input-group-append v-if="hasEditLink">
                        <b-link
                            :href="`${baseURL}${routes[m]}/create`"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-plus"></i>
                            create
                        </b-link>
                    </b-input-group-append>
                </b-input-group>

                <b-table-simple outlined hover id="relations">
                    <fj-colgroup :icons="['check']" :cols="cols[m]" />

                    <tbody>
                        <tr
                            v-for="(item, key) in items[m]"
                            :key="key"
                            style="cursor: pointer;"
                            @click="selected(item, m)"
                        >
                            <b-td
                                style="vertical-align: middle;"
                                v-for="(col, ckey) in cols[m]"
                                :key="ckey"
                                :class="
                                    col.key == 'drag'
                                        ? 'fjord-draggable__dragbar'
                                        : ''
                                "
                            >
                                <div
                                    class="custom-control custom-radio"
                                    v-if="col.key == 'check' && !hasMany"
                                >
                                    <input
                                        type="radio"
                                        autocomplete="off"
                                        class="custom-control-input pointer-events-none"
                                        value=""
                                        :checked="itemChecked(item, m)"
                                    />
                                    <label class="custom-control-label"></label>
                                </div>
                                <b-checkbox
                                    class="pointer-events-none"
                                    v-else-if="col.key == 'check' && hasMany"
                                    :checked="itemChecked(item, m)"
                                />
                                <fj-table-col v-else :item="item" :col="col" />
                            </b-td>
                        </tr>
                    </tbody>
                </b-table-simple>
            </b-tab>
        </b-tabs>
    </b-modal>
</template>

<script>
import TranslatableEloquent from '@fj-js/eloquent/translatable';
import TableModel from '@fj-js/eloquent/table.model';
import { mapGetters } from 'vuex';

export default {
    name: 'FormRelationModal',
    props: {
        field: {
            type: Object,
            required: true,
        },
        model: {
            required: true,
            type: Object,
        },
        hasMany: {
            type: Boolean,
            default: true,
        },
        selectedModels: {
            type: [Object, Array],
            default: () => {
                return {};
            },
        },
    },
    data() {
        return {
            busy: true,
            cols: {},
            items: {},
            search: null,
            models: [],
            routes: {},
            currentModel: '',
        };
    },
    beforeMount() {
        console.log(this.field.models);
        if ('models' in this.field) {
            this.models = this.field.models;
        } else {
            this.models = [this.field.model];
        }
        if ('routes' in this.field) {
            this.routes = this.field.routes;
        } else {
            this.routes = { [this.models[0]]: this.field.route };
        }
        this.currentModel = this.models[0];

        this.setCols();
    },
    mounted() {
        this.loadRelations();
    },
    methods: {
        setCols() {
            for (let key in this.models) {
                let model = this.models[key];
                this.cols[model] = [];
                this.cols[model].push({ key: 'check' });

                let preview = this.field.preview;
                if ('models' in this.field) {
                    preview = this.field.preview[model];
                }
                for (let i = 0; i < preview.length; i++) {
                    let col = preview[i];

                    if (typeof col == typeof '') {
                        col = { key: col };
                    }
                    this.cols[model].push(col);
                }
            }
        },
        itemChecked(item, m) {
            if (!(m in this.selectedModels)) {
                return false;
            }
            return this.selectedModels[m].find((model) =>
                model ? model.id == item.id : false
            )
                ? true
                : false;
        },
        selected(item, m) {
            if (this.itemChecked(item) && this.hasMany) {
                this.$emit('remove', item.id, m);
            } else {
                this.$emit('selected', item, m);
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
            for (let model in this.routes) {
                let route = this.routes[model];
                let response = await axios.get(`${route}/all`);

                let items = [];
                for (let i = 0; i < response.data.length; i++) {
                    items.push(new TableModel(response.data[i]));
                }
                this.items[model] = items;
            }
            this.$forceUpdate();
        },
    },
    watch: {
        search(needle) {
            $('#relations tbody tr').each(function () {
                let row = $(this);
                let haystack = row.text();

                if (!haystack.search(needle)) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        },
    },
    computed: {
        ...mapGetters(['baseURL', 'formConfig']),
        createText() {
            return this.field.form
                ? this.$t('fj.create_model', {
                      model: this.field.form.names.singular,
                  })
                : this.field.model.split('\\').pop();
        },
        title() {
            return this.field.form
                ? this.field.form.names.plural
                : this.field.title;
        },
    },
};
</script>

<style lang="scss">
.pointer-events-none {
    pointer-events: none;
}
.nav-tabs.hide {
    display: none;
}
</style>
