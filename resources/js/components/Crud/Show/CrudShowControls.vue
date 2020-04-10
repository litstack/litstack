<template>
    <b-card class="fj_crud-show__controls">
        <b-row>
            <b-col cols="12" class="pb-3" v-if="isMultilanguage">
                <b class="text-muted d-block pb-1">
                    {{ $t('fj.select_language') }}
                </b>
                <fj-base-language />
            </b-col>
            <b-col cols="12" class="pb-3" v-if="!formConfig.readonly">
                <b class="text-muted d-block pb-1">
                    {{ $t('fj.save_changes') }}
                </b>
                <b-button
                    variant="primary"
                    size="sm"
                    :disabled="!canSave"
                    @click="saveAll"
                >
                    <i class="fas fa-save"></i>
                    {{ buttonText }}
                </b-button>
                <b-button size="sm" @click="loadModel">
                    <fa-icon icon="undo" />
                </b-button>
            </b-col>
            <b-col cols="12" v-if="lastEdit">
                <span
                    class="text-muted pb-1 d-block"
                    v-html="
                        $t(`fj.last_edited`, {
                            time: lastEdit.time,
                            user: lastEdit.user.name
                        })
                    "
                />
            </b-col>

            <b-col cols="12">
                <slot name="controls" />
            </b-col>
        </b-row>
    </b-card>
</template>

<script>
import { mapGetters } from 'vuex';
import FjordModel from '@fj-js/eloquent/fjord.model';

export default {
    name: 'CrudShowControls',
    props: {
        formConfig: {
            type: Object,
            required: true
        },
        create: {
            type: Boolean,
            default: false
        },
        title: {
            type: String
        }
    },
    data() {
        return {
            lastEdit: null
        };
    },
    methods: {
        async saveAll() {
            await this.$store.dispatch('saveModels');
            this.$bvToast.toast(
                this.$t('fj.model_saved', { model: this.title }),
                {
                    variant: 'success'
                }
            );
        },
        onSaved(results) {
            if (!results) {
                return;
            }

            let item = results[0];
            if ('last_edit' in item.data) {
                this.lastEdit = item.data.last_edit;
            }
            console.log(item);
        },
        getLastEdit() {
            if (this.crud.model instanceof FjordModel) {
                this.lastEdit = this.crud.model.last_edit;
                return;
            } else if (this.form) {
                this.lastEdit = this.crud.model.items.items[0].last_edit;
                return;
            }

            this.lastEdit = null;
        },
        loadModel() {
            this.$bus.$emit('loadModel');
        }
    },
    beforeMount() {
        this.$bus.$on('modelsSaved', this.onSaved);
    },
    mounted() {
        this.getLastEdit();
        let self = this;
        document.addEventListener(
            'keydown',
            function(e) {
                if (
                    (window.navigator.platform.match('Mac')
                        ? e.metaKey
                        : e.ctrlKey) &&
                    e.keyCode == 83
                ) {
                    e.preventDefault();
                    if (self.canSave) {
                        self.saveAll();
                    }
                }
            },
            false
        );
    },
    computed: {
        ...mapGetters(['canSave', 'language', 'languages', 'crud', 'form']),
        isMultilanguage() {
            return this.languages.length > 1;
        },
        buttonText() {
            return this.create
                ? this.$t('fj.create_model', { model: this.title })
                : this.$t('fj.save_model', { model: this.title });
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';

.fj_crud-show__controls {
    position: sticky !important;
}

div#fjord-app.vertical {
    .fj_crud-show__controls {
        top: $grid-gutter-width;
    }
}

div#fjord-app.horizontal {
    .fj_crud-show__controls {
        top: $topbar-height + $grid-gutter-width;
    }
}
</style>
