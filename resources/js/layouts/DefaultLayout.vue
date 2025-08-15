<script setup lang="ts">
import AppearanceTabs from '@/components/partials/ApperaanceTabs.vue';
import FooterLayout from './default/FooterLayout.vue';
import NavbarLayout from './default/NavbarLayout.vue';

</script>

<template>
    <NavbarLayout>
        <template #navbar>
            <AppearanceTabs />
        </template>
    </NavbarLayout>
    <slot />
    <FooterLayout />
</template>