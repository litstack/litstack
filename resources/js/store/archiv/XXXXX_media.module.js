import Vue from 'vue';

//import api-services
import { MediaService } from '@fj-js/common/api.service';

const initialState = {
    language: ''
};

const getters = {
    media(state) {
        return state.media;
    }
};

export const state = Object.assign({}, initialState);

export const actions = {
    [ACTIONS_TYPE.FETCH_MEDIA]({ commit }, id) {
        return MediaService.get(id).then(({ data }) => {
            commit(SET_MEDIA, data);
        });
    }
};

export const mutations = {
    [MUTATIONS_TYPE.SET_MEDIA](state, data) {
        state.media = data;
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};
