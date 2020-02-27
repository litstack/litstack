<template>
    <fj-form-item :field="field">
        <b-card class="fjord-block no-fx mb-2">
            <div>
                <b-table-simple outlined table-variant="light">
                    <tr
                        class="draggable-tr"
                        v-for="(relation, rkey) in relations"
                        :key="rkey"
                    >
                        <b-td
                            style="vertical-align: middle;"
                            v-for="(field, key) in fields"
                            :key="`td-${key}`"
                            class="position-relative"
                            :class="
                                field.key == 'drag'
                                    ? 'fjord-draggable__dragbar'
                                    : ''
                            "
                        >
                            <fj-table-col :item="relation" :col="field" />
                        </b-td>
                    </tr>
                </b-table-simple>
            </div>
            <fj-form-belongs-to-many-modal
                :field="field"
                :selectedModels="relations"
                @toggle="updateRelation"
            />
        </b-card>
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormBelongsToMany',
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
            relations: [],
            fields: []
        };
    },
    beforeMount() {
        this.setFields();
        this.fetchRelated();
    },
    methods: {
        setFields() {
            for (let i = 0; i < this.field.preview.length; i++) {
                let field = this.field.preview[i];

                if (typeof field == typeof '') {
                    field = { key: field };
                }
                this.fields.push(field);
            }
        },
        async fetchRelated() {
            try {
                const { data } = await axios.post(
                    `/belongs-to-many/relations`,
                    {
                        model: this.model.model,
                        id: this.model.id,
                        foreign: this.field.id
                    }
                );

                this.relations = data;
            } catch (e) {}
        },
        async updateRelation(item) {
            let payload = {
                model: this.model.model,
                id: this.model.id,
                foreign: this.field.id,
                foreign_id: item.id
            };
            try {
                const { data } = await axios.post(
                    '/belongs-to-many/update',
                    payload
                );

                if (data['detached'].length) {
                    let index = _.findIndex(this.relations, ['id', item.id]);
                    this.relations.splice(index, 1);
                }
                if (data['attached'].length) {
                    this.relations.push(item);
                }
            } catch (e) {}
        }
    }
};
</script>
