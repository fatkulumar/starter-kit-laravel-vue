<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { useEventStore } from '@/stores/student/eventStore';
import { onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3'
import CardMyEvent from '@/components/student/CardMyEvent.vue';

const page = usePage<any>()
const eventStore = useEventStore()

onMounted(() => {
    eventStore.fetchEvents(page.props?.auth?.user?.id)
})

</script>

<template>
    <DefaultLayout>
        Event Ku
        <div class="flex gap-2 my-4">
            <CardMyEvent v-for="(item, index) in eventStore.events" :key="index" :event="item" />
        </div>
    </DefaultLayout>
</template>