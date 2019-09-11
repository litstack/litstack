<template>
    <div class="col-12 col-md-3 order-md-2 pb-4 mb-md-0">
        <div class="fjord-pagecontrols card">
            <div class="card-header">
                <i class="fas fa-sliders-h text-primary pr-2"></i>
                <b>Controls</b>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 pb-3">
                        <b class="text-muted d-block pb-1">
                            Select language
                        </b>
                        <fj-lang-select
                            :languages="lngs"
                            :currentLanguage="lng"
                        />
                    </div>
                    <div class="col-12">
                        <b class="text-muted d-block pb-1">
                            Save changes
                        </b>
                        <button
                            class="btn btn-sm btn-primary"
                            :disabled.prop="!canSave"
                            @click="saveAll"
                        >
                            <i class="fas fa-save"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'Controlls',
    methods: {
        saveAll() {
            this.$store.dispatch('saveModels');
        },
    },
    computed: {
        ...mapGetters(['canSave', 'lng', 'lngs'])
    },
}
</script>
