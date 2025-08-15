<script setup lang="ts">
import CardEvent from '@/components/partials/CardEvent.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { useEventStore } from '@/stores/public/evenStore';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const eventStore = useEventStore();

onMounted(() => {
    eventStore.fetchEventsPublish();
});

</script>

<template>
    <Head title="Beranda" /> 
    <DefaultLayout>
        <div class="flex gap-2 my-4">
            <CardEvent v-for="(item, index) in eventStore.events" :key="index" :event="item" />
        </div>
    </DefaultLayout>
</template>