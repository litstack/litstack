<template>
    <div class="row">
        <div class="col-12">
            <a :href="createLink" class="btn btn-sm btn-primary add-button">
                <i class="fas fa-plus"></i>
                {{ parameters.title }} hinzufügen
            </a>
            <div class="card">
                <div class="card-body">
                    <v-client-table
                        :data="tableData"
                        :columns="columns"
                        :options="options">

                        <a
                            slot="edit"
                            slot-scope="props"
                            class="btn btn-outline-primary btn-sm"
                            :href="editLink(props.row.id)">
                            <i class="fas fa-edit"></i> edit
                        </a>

                    </v-client-table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BaseIndex',
    props: ['parameters', 'tableData', 'columns', 'options'],
    computed: {
        createLink() {
            return `${this.parameters.route}/create`;
        }
    },
    methods: {
        editLink(id) {
            return `${this.parameters.route}/${id}/edit`;
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
