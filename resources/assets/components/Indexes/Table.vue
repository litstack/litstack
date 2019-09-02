<template>
    <div class="row">
        <div class="col-12">
            <a :href="createLink" class="btn btn-sm btn-primary add-button">
                <i class="fas fa-plus"></i>
                add {{ route }}
            </a>
            <div class="card">
                <div class="card-body">
                    <b-table
                        striped
                        hover
                        sort-by="title"
                        :items="items.items.items"
                        :fields="tableFields"
                        :sort-compare="sortCompare"
                    >
                        <b-button-group
                            slot="[actions]"
                            slot-scope="row"
                            size="sm"
                        >
                            <b-button
                                variant="outline-primary"
                                :href="editLink(row.item.id)"
                                v-if="hasAction('edit')"
                            >
                                <fa-icon icon="edit" /> edit
                            </b-button>
                            <b-button
                                variant="outline-danger"
                                @click="deleteItem(row.item)"
                                v-if="hasAction('delete')"
                            >
                                <fa-icon icon="trash" /> delete
                            </b-button>
                        </b-button-group>

                        <template slot="[]" slot-scope="row">
                            {{ row.item[row.field.key] }}
                        </template>
                    </b-table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TableIndex',
    props: {
        items: {
            required: true,
            type: [Array, Object]
        },
        fields: {
            required: true,
            type: Array
        },
        actions: {
            type: Array,
            default: []
        }
    },
    data() {
        return {
            tableFields: {}
        };
    },
    beforeMount() {
        this.tableFields = this.fields;

        if (this.actions.length != 0) {
            this.tableFields.push({
                key: 'actions',
                label: '',
                sortable: false
            });
        }
    },
    computed: {
        route() {
            return window.location.pathname.split('/').splice(-1, 1)[0];
        },
        createLink() {
            return `${this.route}/create`;
        }
    },
    methods: {
        editLink(id) {
            return `${this.route}/${id}/edit`;
        },
        hasAction(action) {
            return this.actions.includes(action);
        },
        deleteItem(item) {
            item.delete();
            this.$notify({
                group: 'general',
                type: 'aw-success',
                title: `Deleted ${item.route}.`,
                text: '',
                duration: 1500
            });
        },
        sortCompare(
            aRow,
            bRow,
            key,
            sortDesc,
            formatter,
            compareOptions,
            compareLocale
        ) {
            const a = aRow[key]; // or use Lodash `_.get()`
            const b = bRow[key];

            if (
                (typeof a === 'number' && typeof b === 'number') ||
                (a instanceof Date && b instanceof Date)
            ) {
                // If both compared fields are native numbers or both are native dates
                return a < b ? -1 : a > b ? 1 : 0;
            } else {
                // Otherwise stringify the field data and use String.prototype.localeCompare
                return this.toString(a).localeCompare(
                    this.toString(b),
                    compareLocale,
                    compareOptions
                );
            }
        },
        toString(value) {
            if (value === null || typeof value === 'undefined') {
                return '';
            } else if (value instanceof Object) {
                return Object.keys(value)
                    .sort()
                    .map(key => toString(value[key]))
                    .join(' ');
            } else {
                return String(value);
            }
        }
    }
};
</script>

<style lang="scss">
table {
    thead {
        th:last-child {
            width: 85px;
        }
    }
    tbody {
    }
}
.add-button {
    position: absolute;
    top: -50px;
    right: 15px;
}
.VueTables__search-field {
    label {
        display: none;
    }
}
</style>
