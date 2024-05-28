<script setup lang="ts">
import { ref } from "vue";

const startDate = ref(new Date()),
    endDate = ref(new Date()),
    endRepeatDate = ref(new Date());

endDate.value.setDate(endDate.value.getDate() + 1);
endRepeatDate.value.setDate(endRepeatDate.value.getDate() + 1);
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

                    <div class="row">
                        <div class="col-lg-6">
                            <label for="task-start-time" class="form-label text-lato mt-2">Start Time</label>
                            <VDatePicker v-model="startDate" mode="dateTime" :input-debounce="500">
                                <template #default="{ inputValue, inputEvents }">
                                    <input class="form-control text-lato" id="task-start-time" :value="inputValue" v-on="inputEvents" />
                                </template>
                            </VDatePicker>
                        </div>

                        <div class="col-lg-6">
                            <label for="task-end-time" class="form-label text-lato mt-2">End Time</label>
                            <VDatePicker v-model="endDate" mode="dateTime" :input-debounce="500">
                                <template #default="{ inputValue, inputEvents }">
                                    <input class="form-control text-lato" id="task-end-time" :value="inputValue" v-on="inputEvents" />
                                </template>
                            </VDatePicker>
                        </div>
                    </div>

                    <label for="task-repeat" class="form-label text-lato mt-2">Repeat</label>
                    <select id="task-repeat" class="form-control text-lato">
                        <option class="text-lato" value="no-repeat">Does not repeat</option>
                        <option class="text-lato" value="everyday">Every Day</option>
                        <option class="text-lato" value="everyweek">Every Week</option>
                        <option class="text-lato" value="everymonth">Every Month</option>
                        <option class="text-lato" value="everyyear">Every Year</option>
                    </select>

                    <label for="task-end" class="form-label text-lato mt-2">Ends</label>
                    <div class="d-block">
                        <div class="form-check d-inline-block">
                            <input class="form-check-input" type="radio" id="task-end-never" value="never" name="task-end" />
                            <label for="task-end-never" class="form-label text-lato">Never</label>
                        </div>

                        <div class="form-check d-inline-block ms-4">
                            <input class="form-check-input" type="radio" id="task-end-on" value="on" name="task-end" />
                            <label for="task-end-on" class="form-label text-lato">On</label>
                        </div>

                        <div class="form-check d-inline-block">
                            <VDatePicker v-model="endRepeatDate" mode="dateTime" :input-debounce="500">
                                <template #default="{ inputValue, inputEvents }">
                                    <input class="form-control text-lato d-inline-block" id="task-end-repeat-date" :value="inputValue" v-on="inputEvents" />
                                </template>
                            </VDatePicker>
                        </div>
                    </div>

                    <label for="task-type" class="form-label text-lato">Type</label>
                    <div class="d-block">
                        <div class="form-check d-inline-block">
                            <input type="radio" name="task-type" id="task-type" class="form-check-input" value="task" />
                            <label for="task-type" class="form-label text-lato">Task</label>
                        </div>

                        <div class="form-check d-inline-block ms-4">
                            <input type="radio" name="task-type" id="task-type" class="form-check-input" value="schedule" />
                            <label for="task-type" class="form-label text-lato">Schedule</label>
                        </div>
                    </div>

                    <div align="right">
                        <button class="btn clr-secondary text-lato brdr-dark">Submit New Task</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>