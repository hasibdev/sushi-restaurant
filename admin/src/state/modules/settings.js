import axios from '@/plugins/axios'
export const state = {
    data: null,
}

export const mutations = {
    SET_SETTINGS(state, newValue) {
        state.data = newValue
    },
}

export const getters = {

}

export const actions = {
    init({ state, dispatch }) {
        dispatch('validate', state)
    },
    async validate({ commit }) {
        try {
            const res = await axios.get('/settings')
            commit('SET_SETTINGS', res.data)
        } catch (error) {
            console.log(error)
        }
    },
}

