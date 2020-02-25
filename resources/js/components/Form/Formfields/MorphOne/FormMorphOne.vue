<template>
    <fj-form-item :field="field" :model="model">
        <b-card class="fjord-block no-fx mb-2">
            <b-row>
                <b-col cols="6">
                    <b-form-select v-model="morphModel" @change="loadModel">
                        <option
                            :value="model"
                            v-for="(model, key) in field.models"
                        >
                            {{ key }}
                        </option>
                    </b-form-select>
                </b-col>
                <b-col cols="6" v-if="rows">
                    <b-form-select v-model="model_id" @change="storeMorphOne">
                        <option :value="model.id" v-for="model in rows">
                            {{ model.title }}
                        </option>
                    </b-form-select>
                </b-col>
            </b-row>
        </b-card>
        <slot />
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormMorphOne',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            relation: {},
            cols: [],

            // Model or QueryBuilder
            morphModel: null,
            rows: null,
            model_name: null,
            model_id: null
        };
    },
    beforeMount() {
        // TODO: this doesn't work with query builders, yet
        //
        this.morphModel = this.model.attributes[`${this.field.morph}_type`];

        if (this.morphModel) {
            this.loadModel().then(() => {
                this.model_id = this.model.attributes[`${this.field.morph}_id`];
            });
        }

        for (let i = 0; i < this.field.preview.length; i++) {
            let col = this.field.preview[i];

            if (typeof col == typeof '') {
                col = { key: col };
            }
            this.cols.push(col);
        }
    },
    methods: {
        async loadModel() {
            let payload = {
                model_type: this.model.model,
                id: this.field.id,
                model_name: _.invert(this.field.models)[this.morphModel]
            };
            try {
                const { data } = await axios.post('morph-one/', payload);

                this.model_name = data.model;
                this.model_id = null;
                this.rows = data.rows;
            } catch (e) {
                console.log(e);
            }
        },
        async storeMorphOne() {
            let payload = {
                model: this.model.model,
                id: this.model.attributes.id,
                morph: this.field.morph,
                morph_model: this.model_name,
                morph_id: this.model_id
            };
            try {
                const { data } = await axios.post('morph-one/store', payload);

                this.$bvToast.toast(this.$t('relation_set'), {
                    variant: 'success'
                });
            } catch (e) {
                this.$bvToast.toast(e, {
                    variant: 'danger',
                    noAutoHide: true
                });
            }
        }
    }
};
</script>
