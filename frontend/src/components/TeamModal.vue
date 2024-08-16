<script>
    
    export default {
        components: {
        },

        setup() {

        },
    }
</script>

<template>
    <main>
        <div class="mainScreen">
            <slot name="teams"></slot>
            <slot name="result"></slot>
            <slot name="predictions"></slot>
        </div>
        <slot name="controller"></slot>
    </main>
</template>

<style scoped>

</style>