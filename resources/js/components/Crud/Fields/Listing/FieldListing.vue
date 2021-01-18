<template>
    <lit-base-field :field="field">
        <div
            v-for="(v, i) in val || []"
            :key="i"
            class="w-100 lit-block d-flex align-items-center mb-3"
        >
            <div class="row w-100 mr-2">
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
            <b-button
                variant="transparent"
                v-b-tooltip
                :title="__('base.item_delete', { item: 'Item' })"
                size="sm"
                class="btn-square lit-block-delete"
                @click="remove(i)"
            >
                <lit-fa-icon icon="trash" />
            </b-button>
        </div>

        <b-button @click="add(val.length)" size="sm">
            <lit-fa-icon icon="plus" />
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
            currentLocale: '',
        };
    },
    watch: {
        value() {
            this.val = this.value;
        },
    },
    beforeMount() {
        if (this.value) {
            this.val = this.value;
        }

        this.makeModel();

        Lit.bus.$on('languageChanged', () => {
            this.$nextTick(this.updateValues);
        });
    },
    mounted() {
        this.updateValues();
    },
    methods: {
        updateValues() {
            this.val = [];
            if (this.value) {
                this.val = this.value;
            }
            for (let i = 0; i < this.val.length; i++) {
                for (let j = 0; j < this.field.fields.length; j++) {
                    let field = this.field.fields[j];
                    let ref = this.$refs[`field.${i}.${j}`][0];
                    // console.log('updateValues', this.val[i][field.id]);
                    ref.$emit('input', this.val[i][field.id]);
                }
            }
        },
        /**
         * Add field.
         */
        add(n, l = 0) {
            if (!this.currentLocale) {
                this.currentLocale = this.$store.state.config.language;
            }

            let locales = this.$store.getters.languages;

            if (locales.length <= l) {
                console.log('Go back to locale:', this.currentLocale);
                this.$store.commit('SET_LANGUAGE', this.currentLocale);
                this.currentLocale = null;

                this.$nextTick(() => {
                    this.updateValues();
                });

                return;
            }

            this.addForLocale(n, locales[l], () => {
                this.add(n, l + 1);
            });
        },
        addForLocale(n, locale, callback) {
            if (!locale) {
                return;
            }
            this.$store.commit('SET_LANGUAGE', locale);

            this.$nextTick(() => {
                console.log('add', { n, locale });
                if (this.val.length <= n) {
                    this.val.push(this.empty());
                }

                this.$nextTick(() => {
                    for (let j = 0; j < this.field.fields.length; j++) {
                        let field = this.field.fields[j];
                        let ref = this.$refs[`field.${n}.${j}`][0];
                        ref.$emit('input', this.val[n][field.id]);
                    }
                    if (typeof callback === 'function') {
                        callback();
                    }
                });
            });
        },
        remove(n, l = 0) {
            if (!this.currentLocale) {
                this.currentLocale = this.$store.state.config.language;
            }

            let locales = this.$store.getters.languages;

            if (locales.length <= l) {
                console.log('Go back to locale:', this.currentLocale);
                this.$store.commit('SET_LANGUAGE', this.currentLocale);
                this.currentLocale = null;

                this.$nextTick(() => {
                    this.updateValues();
                });

                return;
            }

            this.spliceForLocale(n, locales[l], () => {
                this.remove(n, l + 1);
            });
        },
        spliceForLocale(n, locale, callback) {
            if (!locale) {
                return;
            }
            this.$store.commit('SET_LANGUAGE', locale);

            this.$nextTick(() => {
                console.log('remove', { n, locale });
                this.val.splice(n, 1);
                this.$emit('input', this.val);
                if (typeof callback === 'function') {
                    callback();
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
            // console.log('changed', { n, attribute, newValue });
            value[parseInt(n)][attribute] = newValue;
            this.$emit('input', value);
            console.log('input', { value });
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
