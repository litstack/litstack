<template>
    <lit-base-field :field="field">
        <div v-for="(v, i) in val || []" class="w-100">
            <div class="row">
                <lit-field
                    v-for="(field, j) in field.fields"
                    :ref="`field.${i}.${j}`"
                    :key="`${i}-${j}`"
                    :field="{
                        ...Lit.clone(field),
                        id: `${field.id}_${i}`,
                        local_key: `${field.id}_${i}`,
                        storable: false,
                    }"
                    :model="fieldModel"
                    @changed="changed(i, field.id, $event)"
                />
            </div>
            <b-button @click="remove(i)">
                -
            </b-button>
        </div>

        <b-button @click="add(val.length)">
            +
        </b-button>
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldListing',
    props: {
        field: {
            required: true,
            type: Object,
        },
        value: {
            required: true,
        },
        model: {
            required: true,
        },
    },
    data() {
        return {
            fieldModel: {},
            fieldCount: 0,
            val: [],
        };
    },
    beforeMount() {
        if (this.value) {
            this.val = this.value;
        }

        this.makeModel();
        this.addInitialRows();

        Lit.bus.$on('languageChanged', () => {
            this.$nextTick(this.updateValues);
        });
    },
    methods: {
        addInitialRows() {
            if (!this.val) {
                return;
            }

            for (let i = 0; i < this.val.length; i++) {
                this.add(i);
            }
        },
        remove(n) {
            this.val.splice(n, 1);
            this.updateValues();
        },
        updateValues() {
            this.val = [];
            if (this.value) {
                this.val = this.value;
            }
            for (let i = 0; i < this.val.length; i++) {
                for (let j = 0; j < this.field.fields.length; j++) {
                    let field = this.field.fields[j];
                    let ref = this.$refs[`field.${i}.${j}`][0];
                    console.log('updateValues', this.val[i][field.id]);
                    ref.$emit('input', this.val[i][field.id]);
                }
            }
        },
        /**
         * Add field.
         */
        add(n) {
            if (this.val.length <= n) {
                this.val.push(this.empty());
            }

            this.$nextTick(() => {
                for (let j = 0; j < this.field.fields.length; j++) {
                    let field = this.field.fields[j];
                    let ref = this.$refs[`field.${n}.${j}`][0];
                    console.log('add', this.val[n][field.id]);
                    console.log('add2', this.val[n]);
                    ref.$emit('input', this.val[n][field.id]);
                }
            });
        },
        empty() {
            let data = {};

            for (let i = 0; i < this.field.fields.length; i++) {
                let field = this.field.fields[i];
                data[field.id] = null;
            }

            return data;
        },
        changed(n, attribute, newValue) {
            let value = this.val || [];
            if (typeof value[n] !== 'object') {
                value[n] = {};
            }
            console.log('changed', { n, attribute, newValue });
            value[parseInt(n)][attribute] = newValue;
            this.$emit('input', value);
        },
        makeModel() {
            this.fieldModel = this.crud({
                attributes: { id: this.model.id },
                translatable: false,
                cast: true,
            });
        },
    },
};
</script>
