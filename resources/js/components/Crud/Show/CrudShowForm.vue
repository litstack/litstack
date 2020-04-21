<template>
    <form class="row" style="margin-bottom: -1.5em;">
        <template v-for="id in fieldIds">
            <fj-crud-show-form-item
                v-if="getFieldById(id)"
                :field="getFieldById(id)"
                :model="getModelByFieldId(id)"
            />
        </template>
    </form>
</template>

<script>
import { mapGetters } from 'vuex';
import FjordModel from '@fj-js/eloquent/fjord.model';

export default {
    name: 'CrudShowForm',
    props: {
        model: {
            required: true,
            type: Object
        },
        config: {
            required: true,
            type: Object
        },
        fieldIds: {
            type: Array,
            required: true
        },
        title: {
            type: String
        },
        cols: {
            type: Number,
            default() {
                return 12;
            }
        }
    },
    data() {
        return {
            preparedModels: []
        };
    },
    beforeMount() {
        this.init();

        this.$bus.$on('modelLoaded', () => {
            this.init();
        });
    },
    methods: {
        init() {
            let model = this.model || this.crud.model;

            if (model instanceof FjordModel) {
                this.preparedModels = [model];
            } else {
                this.preparedModels = model.items.items;
            }
        },
        getFieldById(id) {
            for (let i in this.preparedModels) {
                let field = this.preparedModels[i].getFieldById(id);
                if (field) {
                    return field;
                }
            }
        },
        getModelByFieldId(id) {
            for (let i in this.preparedModels) {
                let field = this.preparedModels[i].getFieldById(id);
                if (field) {
                    return this.preparedModels[i];
                }
            }
        }
    },
    computed: {
        ...mapGetters(['crud', 'form'])
    }
};
</script>
