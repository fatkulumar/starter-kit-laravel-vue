<script setup lang="ts">
import Profile from '@/components/partials/Profile.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';

interface Props {
    status?: string;
}

defineProps<Props>();

</script>

<template>
    <DefaultLayout>
        <Profile :must-verify-email="true" :status="status" />
    </DefaultLayout>
</template>