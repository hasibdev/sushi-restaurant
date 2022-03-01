import axios from '@/plugins/axios'
export const state = {
    currentUser: null,
}

export const mutations = {
    SET_CURRENT_USER(state, newValue) {
        state.currentUser = newValue
    },
}

export const getters = {
    // Whether the user is currently logged in.
    loggedIn(state) {
        return !!state.currentUser
    },
}

export const actions = {
    init({ state, dispatch }) {
        dispatch('validate', state)
    },
    async validate({ commit }) {
        const userid = window.jara.getCookie('userid')
        try {
            const resUser = await axios.get(`/users/${userid}`)
            commit('SET_CURRENT_USER', resUser.data.payload)
        } catch (error) {
            console.log(error)
        }
    },
}

