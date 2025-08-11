<script setup lang="ts">
import AppearanceTabs from '@/components/partials/ApperaanceTabs.vue';
import CardEvent from '@/components/partials/CardEvent.vue';
import { useEventStore } from '@/stores/public/evenStore';
import { onMounted } from 'vue';

const eventStore = useEventStore();

onMounted(() => {
    eventStore.fetchEvents();
});

</script>

<template>
    <AppearanceTabs />
    <div class="flex gap-2">
        <CardEvent v-for="(item, index) in eventStore.events" :key="index" :event="item" />
    </div>
</template>