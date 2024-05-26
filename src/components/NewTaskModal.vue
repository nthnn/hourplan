<script setup lang="ts">
import { ref } from "vue";

const startDate = ref(new Date());
</script>

<template>
    <div class="modal fade" id="newTaskModal" tabindex="-1" aria-labelledby="newTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-body">
                    <h3 class="mb-4">Add New Task</h3>

                    <label for="task-title" class="form-label text-lato">Title</label>
                    <input type="text" class="form-control text-lato" id="task-title" placeholder="Add Title" />

                    <label for="task-desc" class="form-label text-lato mt-2">Description</label>
                    <input type="text" class="form-control text-lato" id="task-desc" placeholder="What your task is about?" />

                    <label for="task-start-time" class="form-label text-lato mt-2">Start Time</label>
                    <VDatePicker id="task-start-time" v-model="startDate" mode="dateTime" :input-debounce="500" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <input class="form-control" :value="inputValue" v-on="inputEvents" />
                        </template>
                    </VDatePicker>
                </div>
            </div>
        </div>
    </div>
</template>