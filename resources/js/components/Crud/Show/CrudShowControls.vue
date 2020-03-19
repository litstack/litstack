<template>
    <b-card class="fj_crud-show__controls">
        <b-row>
            <b-col cols="12" class="pb-3" v-if="isMultilanguage">
                <b class="text-muted d-block pb-1">
                    {{ $t('select_language') }}
                </b>
                <fj-base-language />
            </b-col>
            <b-col cols="12">
                <b class="text-muted d-block pb-1">
                    {{ $t('save_changes') }}
                </b>
                <b-button
                    variant="primary"
                    size="sm"
                    :disabled="!canSave"
                    @click="saveAll"
                    ><i class="fas fa-save"></i> {{ buttonText }}</b-button
                >
                <b-button size="sm" @click="loadModel">
                    <fa-icon icon="undo" />
                </b-button>
            </b-col>

            <b-col cols="12">
                <slot name="controls" />
            </b-col>
        </b-row>
    </b-card>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'CrudShowControls',
    props: {
        create: {
            type: Boolean,
            default: false
        },
        title: {
            type: String
        }
    },
    methods: {
        async saveAll() {
            await this.$store.dispatch('saveModels');
            this.$bvToast.toast(this.$t('model_saved', { model: this.title }), {
                variant: 'success'
            });
        },
        loadModel() {
            this.$bus.$emit('loadModel');
        }
    },
    mounted() {
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
        ...mapGetters(['canSave', 'language', 'languages']),
        isMultilanguage() {
            return this.languages.length > 1;
        },
        buttonText() {
            return this.create
                ? this.$t('create_model', { model: this.title })
                : this.$t('save_model', { model: this.title });
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
