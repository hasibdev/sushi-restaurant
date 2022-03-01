<template>
    <button :type="type" :disabled="loading" ref="primary-button" @click.prevent="$emit('click')" :class="`btn btn-${variant} px-4 d-flex justify-content-center align-items-center`">
        <b-spinner v-if="loading" small label="Spinning" variant="white"></b-spinner>
        <slot v-else></slot>
    </button>
</template>

<script>
export default {
    mounted() {
        // Set permanent width for button
        // this width will stay permanent in loading
        this.$nextTick(() => {
            setTimeout(() => {
                const btn = this.$refs["primary-button"]
                btn.style.width = `${btn.offsetWidth + 1}px`
            }, 0)
        })
    },
    props: {
        variant: {
            type: String,
            default: "primary",
        },
        loading: {
            type: Boolean,
            default: false,
        },
        type: {
            type: String,
            default: 'submit'
        }
    },
};
</script>

<style lang="scss" scoped>
</style>
