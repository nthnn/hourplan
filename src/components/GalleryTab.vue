<script lang="ts">
export default {
    props: {
        show: {type: Boolean}
    }
};
</script>

<template>
    <div v-if="show" class="container">
        <h1>Gallery</h1>
    </div>
</template>