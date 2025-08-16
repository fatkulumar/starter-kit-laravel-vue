<script setup lang="ts">
import Accordion from '@/components/student/Accrodion.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { useTryoutStore } from '@/stores/student/tryoutStore';
import { Event } from '@/types/Event';
import { onMounted } from 'vue';

const tryoutStore = useTryoutStore();
const props = defineProps<{ event: Event }>()

onMounted(() => {
    tryoutStore.fetchTryoutPurchasedByEventId(props.event.id);
});

</script>

<template>
    <DefaultLayout>
        <Accordion :tryouts="tryoutStore.tryouts" />
    </DefaultLayout>
</template>