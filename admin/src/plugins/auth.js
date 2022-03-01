import store from '@/state/store.js'
export default () => {
    store.dispatch('auth/init')
}