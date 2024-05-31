<script lang="ts">
import $ from "jquery";
import { toJSDate, toShortDate } from '@/assets/scripts/time';

export default {
    data() {
        return {
            taskId: "task-" + Math.round(Math.random() * 100000)
        }
    },
    props: {
        title: {type: String},
        desc: {type: String},
        color: {type: String},
        emotion: {type: String},
        startDate: {type: Number},
        endDate: {type: Number}
    },
    computed: {
        shortStartDate(): string {
            return toShortDate(toJSDate(this.startDate as number));
        },
        shortEndDate(): string {
            return toShortDate(toJSDate(this.endDate as number));
        }
    },
    methods: {
        onMarked(): void {
            alert($("#" + this.taskId).is(":checked"));
        }
    }
};
</script>

<template>
    <div class="w-100 rounded border-none shadow p-2" :style="{ backgroundColor: color}">
        <div class="row p-2">
            <div class="col-4 col-lg-3">
                <div v-if="emotion === 'happy'">
                    <img src="@/assets/images/cat-happy.png" class="w-100 p-2" />
                </div>
                <div v-else-if="emotion === 'delighted'">
                    <img src="@/assets/images/cat-delighted.png" class="w-100 p-2" />
                </div>
                <div v-else-if="emotion === 'crying'">
                    <img src="@/assets/images/cat-crying.png" class="w-100 p-2" />
                </div>
            </div>

            <div class="col-6 col-lg-7">
                <div class="p-2">
                    <h3 class="text-lato">{{ title }}</h3>
                    <p class="text-lato">
                        {{ desc }}<br/>
                        <small class="text-muted text-lato desktop-only">{{ shortStartDate }} &mdash; {{ shortEndDate }}</small>
                    </p>
                </div>
            </div>

            <div class="col-2">
                <div class="d-flex align-items-center h-100 w-100 m-0 p-0">
                    <input type="checkbox"
                        class="form-check-input" value=""
                        :id=taskId @click="onMarked" />
                </div>
            </div>
        </div>

        <small class="text-lato px-2 mb-2 mobile-only text-muted">{{ shortStartDate }} &mdash; {{ shortEndDate }}</small>
    </div>
    <br/>
</template>

<style scoped>
input[type="checkbox"] {
    width: 28px !important;
    height: 28px !important;
    outline: none !important;
    border: none !important;
    margin-top: -12px;
}

input[type="checkbox"]:checked {
    background-color: #1a172a !important;
}
</style>