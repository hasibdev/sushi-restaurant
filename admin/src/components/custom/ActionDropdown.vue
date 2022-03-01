<template>
    <b-dropdown right no-caret>
        <template #button-content>
            <span class="d-flex align-items-center">
                <!-- <i class="css-menu-right"></i> -->
                <i class="ri-menu-2-line align-middle"></i>
            </span>
        </template>

        <slot />
    </b-dropdown>
</template>

<script>
export default {

}
</script>

<style lang="scss" scoped>
</style>