<script lang="ts" setup>
import { Event } from '@/types/Event';
import { Link } from '@inertiajs/vue3';

defineProps<{
    event: Event
}>();
</script>

<template>
    <div
        class="relative flex flex-col w-96 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300">
        <div class="relative h-56">
            <img :src="event.banner_url" :alt="event.title" class="w-full h-full object-cover" />
        </div>

        <div class="p-5 flex-1 flex flex-col">
            <h3 class="text-lg font-semibold text-slate-800 mb-3 leading-tight line-clamp-2">
                {{ event.title }}
            </h3>

            <Link :href="`tryout/${event.event_code}`" class="w-full py-2 mx-auto bg-button rounded-md text-center">Lihat</Link>
        </div>
    </div>
</template>
