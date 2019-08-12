import Vue from 'vue'
import EloquentCollection from './../eloquent/collection'
import Bus from './../common/event.bus'

const initialState = {
    language: '',
    languages: [],
    modelsToSave: [],
    saveModelIds: {}
}

const getters = {
    lng(state) {
        return state.language
    },
    language(state) {
        return state.language
    },
    lngs(state) {
        return state.languages
    },
    languages(state) {
        return state.languages
    },
    canSave(state) {
        return state.modelsToSave.length > 0
    }
}

export const actions = {
    async ['saveModels']({ commit, state }) {

        let collection = new EloquentCollection({data: []})
        collection.items = collect(state.modelsToSave)
        await collection.save()

        Vue.notify({
            group: 'general',
            type: 'aw-success',
            title: 'Saved successfully.',
            text: '',
            duration: 1500
        });

        Bus.$emit('modelsSaved')

        commit('resetModelsToSave')
    }
}

export const state = Object.assign({}, initialState)

export const mutations = {
    ['setLanguages'](state, languages) {
        state.languages = languages
    },
    ['setLanguage'](state, lng) {
        state.language = lng
        Bus.$emit('languageChanged', state.language)
    },
    ['addModelToSave'](state, {model, id}) {
        if(!state.modelsToSave.includes(model)) {
            state.modelsToSave.push(model)
            state.saveModelIds[model.model] = []
        }
        if(!state.saveModelIds[model.model].includes(id)) {
            state.saveModelIds[model.model].push(id)
        }
    },
    ['removeModelFromSave'](state, {model, id}) {
        if(!state.modelsToSave.includes(model)) {
            return
        }
        if(!state.saveModelIds[model.model].includes(id)) {
            return
        }
        state.saveModelIds[model.model].splice(state.saveModelIds[model.model].indexOf(id), 1)
        if(state.saveModelIds[model.model].length > 0) {
            return
        }
        state.modelsToSave.splice(state.modelsToSave.indexOf(model), 1)
    },
    ['resetModelsToSave'](state, ) {
        state.modelsToSave = []
    }
}

export default {
    state,
    actions,
    mutations,
    getters
}
