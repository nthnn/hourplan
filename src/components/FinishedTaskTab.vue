<script setup lang="ts">
import TaskList from "./TaskList.vue";
</script>

<script lang="ts">
import env from '@/assets/scripts/config';

export default {
    data() {
        return {
            currentTheme: localStorage.getItem("theme") as string,
            publicPath: env.host.split("/")[0] + "/"
        }
    },
    props: {
        show: {type: Boolean}
    }
};
</script>

<template>
    <div v-if="show" class="container">
        <h1>Finished Tasks</h1>
        <hr/>

        <div id="finished-tasks">
            <TaskList apiAction="all_task" :finished="true" :large="true" />
        </div>

        <div id="no-finished-tasks" align="center">
            <img :src="`${publicPath}images/cat-happy-${currentTheme}.png`" width="120" />
            <p>No finished task yet.</p>
        </div>
    </div>
</template>