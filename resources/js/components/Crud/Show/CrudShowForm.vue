<template>
    <fj-crud-show-form-item :field="field" :model="getModel()" />
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
        field: {
            type: Object,
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
        getModel() {
            for (let i in this.preparedModels) {
                let field = this.preparedModels[i].getFieldById(this.field.id);
                if (field) {
                    this.field.route_prefix = this.field.route_prefix.replace(
                        '{id}',
                        this.preparedModels[i].id
                    );
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
