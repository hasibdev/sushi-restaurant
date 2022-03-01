import store from '@/state/store.js'
import i18n from '../i18n'
export default () => {
    // set settings
    store.dispatch('settings/init')

    // Set locale
    const locale = localStorage.getItem('locale') || i18n.fallbackLocale
    i18n.locale = locale
}