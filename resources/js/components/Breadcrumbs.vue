<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import HomeIcon from './partials/HomeIcon.vue';
import RightArrowIcon from './partials/RightArrowIcon.vue';

interface BreadcrumbItemType {
  title: string;
  href?: string;
}

defineProps<{
  breadcrumbs: BreadcrumbItemType[];
}>();
</script>

<template>
  <nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
      <li class="inline-flex items-center">
        <Link
          href="/"
          class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
        >
        <HomeIcon class="w-3 h-3 me-2.5" />
          Home
        </Link>
      </li>

      <template v-for="(item, index) in breadcrumbs" :key="index">
        <li v-if="index !== breadcrumbs.length - 1">
          <div class="flex items-center">
            <RightArrowIcon class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" />
            <Link
              :href="item.href ?? '#'"
              class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white"
            >
              {{ item.title }}
            </Link>
          </div>
        </li>

        <li v-else aria-current="page">
          <div class="flex items-center">
            <RightArrowIcon class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" />
            <span
              class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400"
            >
              {{ item.title }}
            </span>
          </div>
        </li>
      </template>
    </ol>
  </nav>
</template>
