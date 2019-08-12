<template>
    <div class="row fjord-form">
        <div class="col-12">
            <div class="card">
                <div
                    class="card-header d-flex justify-content-end"
                    v-if="parameters.translatable"
                >
                    <div class="btn-group btn-group-sm" role="group">
                        <button
                            class="btn"
                            :class="[
                                {
                                    'btn-primary': lng == language,
                                    'btn-secondary': lng != language
                                }
                            ]"
                            @click="switchLanguage(lng)"
                            v-for="lng in parameters.languages"
                        >
                            {{ lng }}
                        </button>
                    </div>
                </div>
                <form class="card-body">
                    <div class="row">
                        <div
                            :class="fieldWidth(field)"
                            v-for="(field, index) in parameters.fields"
                        >
                            <base-formitem
                                v-if="
                                    field.type ==
                                        ('input' || 'number' || 'email')
                                "
                                :field="field"
                            >
                                <template v-if="translatable(field.id)">
                                    <input
                                        :type="field.type"
                                        class="form-control"
                                        :id="field.id"
                                        :placeholder="field.placeholder"
                                        v-model="payload[language][field.id]"
                                    />
                                    <div class="input-group-append">
                                        <span class="input-group-text">{{
                                            language
                                        }}</span>
                                    </div>
                                </template>
                                <input
                                    :type="field.type"
                                    class="form-control"
                                    :id="field.id"
                                    :placeholder="field.placeholder"
                                    v-model="payload[field.id]"
                                    v-else
                                />
                            </base-formitem>

                            <base-formitem
                                v-if="field.type == 'select'"
                                :field="field"
                            >
                                <select
                                    class="form-control"
                                    v-model="payload[field.id]"
                                >
                                    <option
                                        :value="option.value"
                                        v-for="option in field.options"
                                    >
                                        {{ option.title }}
                                    </option>
                                </select>
                            </base-formitem>

                            <base-formitem
                                v-if="field.type == 'textarea'"
                                :field="field"
                            >
                                <textarea
                                    class="form-control"
                                    :id="field.id"
                                    :rows="field.rows"
                                    :placeholder="field.placeholder"
                                    v-model="payload[language][field.id]"
                                    v-if="translatable(field.id)"
                                ></textarea>
                                <textarea
                                    class="form-control"
                                    :id="field.id"
                                    :rows="field.rows"
                                    :placeholder="field.placeholder"
                                    v-model="payload[field.id]"
                                    v-else
                                ></textarea>
                            </base-formitem>

                            <base-formitem
                                v-if="field.type == 'wysiwyg'"
                                :field="field"
                            >
                                <div
                                    class="input-group"
                                    v-if="translatable(field.id)"
                                >
                                    <ckeditor
                                        :editor="editor"
                                        :config="editorConfig"
                                        v-model="payload[language][field.id]"
                                    ></ckeditor>
                                    <div class="input-group-append">
                                        <span class="input-group-text">{{
                                            language
                                        }}</span>
                                    </div>
                                </div>
                                <ckeditor
                                    :editor="editor"
                                    :config="editorConfig"
                                    v-model="payload[field.id]"
                                    v-else
                                ></ckeditor>
                            </base-formitem>

                            <base-formitem
                                v-if="field.type == 'image'"
                                :field="field"
                            >
                                <base-media
                                    :collection="parameters.route"
                                    :id="id"
                                    :media="data[field.id]"
                                    :data="field"
                                    :method="method"
                                    @updateAttributes="updateAttributes"
                                />
                            </base-formitem>

                            <base-formitem
                                v-if="field.type == 'bool'"
                                :field="field"
                            >
                                <button
                                    @click.prevent="toggle(field.id)"
                                    class="btn btn-lg"
                                >
                                    <i
                                        class="fas fa-toggle-on text-success"
                                        v-if="payload[field.id]"
                                    ></i>
                                    <i
                                        class="fas fa-toggle-off text-danger"
                                        v-else
                                    ></i>
                                </button>
                            </base-formitem>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <button
                                    v-if="controls.includes('delete')"
                                    type="submit"
                                    name="button"
                                    class="btn btn-danger mr-2"
                                    @click.prevent="call('delete')"
                                >
                                    <i class="far fa-save"></i> Löschen
                                </button>
                                <button
                                    v-if="controls.includes('save')"
                                    type="submit"
                                    name="button"
                                    class="btn btn-primary mr-2"
                                    @click.prevent="
                                        call(id ? 'update' : 'create')
                                    "
                                >
                                    <i class="far fa-save"></i> Speichern
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    name: 'BaseCreate',
    props: {
        notifyText: {
            type: Function
        },
        method: {
            type: String,
            required: true
        },
        data: {
            type: Object,
            required: true
        },
        parameters: {
            type: Object,
            required: true
        },
        controls: {
            type: Array,
            default: () => {
                return ['save'];
            }
        }
    },
    data() {
        return {
            payload: {},
            media_attributes: [],
            id: null,
            language: null,
            editor: ClassicEditor,
            editorConfig: {
                removePlugins: [],
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'link',
                        'undo',
                        'redo',
                        'bulletedList',
                        'numberedList',
                        'blockQuote'
                    ]
                }
            }
        };
    },
    beforeMount() {
        this.payload = this.data;

        // if the model is translatable
        if (this.parameters.translatable) {
            // set default language
            this.language = this.parameters.languages[0];
        }
    },
    mounted() {
        this.id = this.data.id !== undefined ? this.data.id : null;
    },
    methods: {
        translatable(id) {
            return (
                this.parameters.translatable &&
                this.parameters.translatedAttributes.includes(id)
            );
        },
        activeLanguage(lng) {
            return lng == this.language;
        },
        switchLanguage(language) {
            this.language = language;
            this.$nextTick(() => {
                console.log(this.payload);
            });
        },
        fieldWidth(field) {
            return field.width !== undefined ? `col-${field.width}` : 'col-12';
        },
        updateAttributes(media) {
            let media_attributes = [];
            for (var i = 0; i < media.length; i++) {
                media[i];
                media_attributes.push({
                    id: media[i].id,
                    custom_properties: media[i].custom_properties
                });
            }
            this.media_attributes = media_attributes;
        },
        toggle(id) {
            if (this.payload.hasOwnProperty(id)) {
                this.payload[id] = !this.payload[id];
            } else {
                this.$set(this.payload, id, true);
            }
        },
        async call(method) {
            let route =
                method == 'create'
                    ? this.parameters.route
                    : `${this.parameters.route}/${this.id}`;

            let response = await CrudApi[method](route, this.payload, {
                notifyText: this.notifyText
            });

            // set ID for created model
            if (method == 'post') {
                this.id = response.data.id;
            }

            /**
             * Save media attributes
             */
            if (this.media_attributes.length > 0) {
                let payload = {
                    media_attributes: this.media_attributes
                };
                axios.put(`/admin/media/attributes`, payload).then(response => {
                    console.log(response.data);
                });
            }
        }
    }
};
</script>
