import Vue from 'vue'

//import api-services
import { MediaService } from './../../common/api.service'

//mutations and actions
import * as ACTIONS_TYPE from './../../store/types/actions.type'
import * as MUTATIONS_TYPE from './../../store/types/mutations.type'

const initialState = {
    media: {
        id : '',
        title: '',
        filename : '',
        original_name : '',
        parent_id: '',
        type: ''
    }
}

const getters = {
    media(state) {
        return state.media
    }
}

export const state = Object.assign({}, initialState)

export const actions = {
    [ACTIONS_TYPE.FETCH_MEDIA] ({ commit }, id) {
        return MediaService.get(id)
            .then(({ data }) => {
                commit(SET_MEDIA, data)
            })
    },
}

export const mutations = {
    [MUTATIONS_TYPE.SET_MEDIA](state, data) {
        state.media = data
    }
}

export default {
    state,
    actions,
    mutations,
    getters
}
