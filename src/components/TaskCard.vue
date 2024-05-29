<script lang="ts">
import { toJSDate, toShortDate } from '@/assets/scripts/time';

export default {
    props: {
        title: {type: String},
        desc: {type: String},
        color: {type: String},
        emotion: {type: String},
        startDate: {type: Number},
        endDate: {type: Number}
    },
    computed: {
        shortStartDate() {
            return toShortDate(toJSDate(this.startDate as number));
        },
        shortEndDate() {
            return toShortDate(toJSDate(this.endDate as number));
        }
    }
};
</script>

<template>
    <div class="w-100 rounded border-none shadow p-2" :style="{ backgroundColor: color}">
        <div class="row">
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
        </div>

        <small class="text-lato px-2 mb-2 mobile-only text-muted">{{ shortStartDate }} &mdash; {{ shortEndDate }}</small>
    </div>
    <br/>
</template>