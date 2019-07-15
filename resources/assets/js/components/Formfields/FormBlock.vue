<template>
    <BaseFormitem :field="field">
        <div class="card no-fx">
            <div class="card-body">
                <draggable
                    v-model="payload"
                    @end="newOrder"
                    handle=".fjord-draggable__dragbar"
                >
                    <div
                        class="fjord-draggable"
                        v-for="(repeatable, index) in payload"
                        :key="repeatable.id"
                    >
                        <div
                            class="fjord-draggable__dragbar d-flex justify-content-center"
                        >
                            <i class="fas fa-grip-horizontal text-muted"></i>
                        </div>
                        <div class="row">
                            <div
                                :class="`col col-${item.width}`"
                                v-for="item in config.repeatables[
                                    repeatable.type
                                ]"
                            >
                                <template
                                    v-if="
                                        config.translatable &&
                                            payload[index][language]
                                    "
                                >
                                    <FormInput
                                        v-if="item.type == 'input'"
                                        :item="item"
                                        :value="
                                            payload[index][language].content[
                                                item.id
                                            ]
                                        "
                                        v-model="
                                            payload[index][language].content[
                                                item.id
                                            ]
                                        "
                                    />
                                    <FormWysiwyg
                                        v-if="item.type == 'wysiwyg'"
                                        :item="item"
                                        :value="
                                            payload[index][language].content[
                                                item.id
                                            ]
                                        "
                                        v-model="
                                            payload[index][language].content[
                                                item.id
                                            ]
                                        "
                                    />
                                </template>
                                <template v-else>
                                    <FormInput
                                        v-if="item.type == 'input'"
                                        :item="item"
                                        :value="payload[index].content[item.id]"
                                        v-model="
                                            payload[index].content[item.id]
                                        "
                                    />
                                    <FormWysiwyg
                                        v-if="item.type == 'wysiwyg'"
                                        :item="item"
                                        :value="payload[index].content[item.id]"
                                        v-model="
                                            payload[index].content[item.id]
                                        "
                                    />
                                </template>
                                <FormImage
                                    v-if="item.type == 'image'"
                                    :item="item"
                                    :data="{
                                        model:
                                            ' AwStudio\\Fjord\\Models\\Repeatable'
                                    }"
                                    :id="payload[index].id"
                                    :value="payload[index].image"
                                    v-model="payload[index].image"
                                />
                            </div>
                        </div>
                    </div>
                </draggable>
                <button
                    class="btn btn-secondary btn-sm mr-2"
                    v-for="type in block.repeatables"
                    @click="addRepeatableOfType(type)"
                >
                    <i class="fas fa-plus"></i> add {{ type }}
                </button>
            </div>
        </div>
    </BaseFormitem>
</template>

<script>
import CrudApi from './../../common/crud.api';

export default {
    name: 'FormBlock',
    props: {
        field: {
            type: Object
        },
        data: {
            type: Array,
            required: true
        },
        config: {
            type: Object,
            required: true
        },
        language: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            block: null,
            payload: []
        };
    },
    beforeMount() {
        this.block = this.field;
        this.payload = this.data;

        this.init();
    },
    watch: {
        payload(val) {
            this.$emit('input', val);
        }
    },
    methods: {
        async addRepeatableOfType(type) {
            // Prepare the payload for the API-Call
            let data = {
                type: type,
                page_name: this.config.page,
                block_name: this.field.id,
                content: this.newRepeatable(type)
            };

            let response = await CrudApi.create('repeatables', data);

            /**
             *  generate correct object structure
             */
            for (var i = 0; i < this.config.languages.length; i++) {
                let lng = this.config.languages[i];
                response.data[lng] = {
                    content: response.data.content
                };
            }
            this.payload.push(response.data);
        },
        newRepeatable(type) {
            let obj = {};

            for (var i = 0; i < this.config.repeatables[type].length; i++) {
                if (this.config.repeatables[type][i].id != 'image') {
                    obj[this.config.repeatables[type][i].id] = '';
                }
            }
            return obj;
        },
        init() {
            if (this.config.translatable == true) {
                for (var p = 0; p < this.payload.length; p++) {
                    //let item = this.payload[p].content;

                    if (this.payload[p].type == 'image') {
                        continue;
                    }

                    for (var i = 0; i < this.config.languages.length; i++) {
                        let lng = this.config.languages[i];
                        let item = _.filter(this.payload[p].translations, [
                            'locale',
                            lng
                        ]);

                        /**
                         *  copy translations to correct structure
                         *  if translation is missing, set to default content
                         */
                        if (item[0] !== undefined) {
                            this.$set(this.payload[p], lng, {
                                content: JSON.parse(
                                    JSON.stringify(item[0].content)
                                )
                            });
                        } else {
                            this.$set(this.payload[p], lng, {
                                content: this.payload[p].content
                            });
                        }
                    }
                }
            }
        },
        newOrder() {
            let payload = {
                model: 'AwStudio\\Fjord\\Models\\Repeatable',
                order: _.map(this.payload, 'id')
            };

            console.log(this.payload);

            axios.put('/admin/order', payload).then(response => {
                console.log('Response: ', response.data);
            });
        }
    }
};
</script>
