export default {
    data() {
        return {
            settings:  JSON.parse(window.localStorage.getItem("settings")) || {},
        }
    },
    methods: {
        titleCase(val) {
            return val.toLocaleLowerCase().split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')
        }
    },
}