<script lang="ts" setup>
import { Event } from '@/types/Event';
import { Link } from '@inertiajs/vue3';
import { BookmarkCheck, Calendar, HandMetal, NotebookPen, LogIn } from 'lucide-vue-next';
import { useTryoutStore } from '@/stores/student/tryoutStore';
import Modal from './Modal.vue';
import Button from '../ui/button/Button.vue';
import ModalUploadRequirements from '../admin/student/ModalUploadRequirements.vue';
import TryoutSelection from '../admin/student/TryoutSelection.vue';
import { usePurchaseStore } from '@/stores/student/purchaseStore';
const tryoutStore = useTryoutStore();
const purchaseStore = usePurchaseStore();

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

            <div class="text-sm text-slate-600 space-y-1 flex-1">
                <div class="flex">
                    <div>
                        <Calendar />
                    </div>
                    <div>
                        <p>Pelaksanaan:</p>
                        <p>{{ event.start_time_formatted }} - {{ event.end_time_formatted }}</p>
                    </div>
                </div>
                <div class="flex">
                    <div>
                        <NotebookPen />
                    </div>
                    <div>
                        <p>Pengerjaan:</p>
                        <p>{{ event.preliminary_date_formatted }}</p>
                    </div>
                </div>
                <div class="flex">
                    <div>
                        <BookmarkCheck />
                    </div>
                    <div>
                        <p>Final:</p>
                        <p>{{ event.final_date_formatted }}</p>
                    </div>
                </div>
                <div class="flex" v-if="event.guidebook_link">
                    <div>
                        <HandMetal />
                    </div>
                    <div>
                        <p>Final:</p>
                        <a :href="event.guidebook_link" target="_blank">Panduan Teknis</a>
                    </div>
                </div>
            </div>

            <div class="mt-5 flex gap-3" v-if="$page.props.auth.user">
                <Button @click="tryoutStore.handleShowModalGetTtryout(event.id)"
                    class="flex-1 rounded-lg bg-indigo-600 py-2 text-center text-sm font-medium text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    Daftar
                </Button>
                <a :href="event.whatsapp_group_link" target="_blank"
                    class="flex-1 rounded-lg bg-green-600 py-2 text-center text-sm font-medium text-white shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400">
                    WhatsApp
                </a>
            </div>
            <div v-else class="w-full">
                <Link href="/login"
                    class="flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow transition duration-200 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                <LogIn />
                Login
                </Link>
            </div>
        </div>

        <!-- modal tryout selection -->
        <Modal :show="tryoutStore.modalGetTryout" @close="tryoutStore.handleCloseModalGetTryout" class="max-w-xl">
            <TryoutSelection :tryouts="tryoutStore.tryouts" :selectedIds="tryoutStore.selectedIds"
                @toggle-select="tryoutStore.toggleSelectOne"
                @show-requirements="tryoutStore.handleShowModalRequirement" :amount="tryoutStore.amount" :tryout-store="tryoutStore" />
        </Modal>


        <!-- modal upload requirement -->
        <Modal :show="purchaseStore.modalUploadRequirements" @close="purchaseStore.handleCloseModalUploadRequirements"
            class="max-w-3xl">
            <ModalUploadRequirements :tasks="purchaseStore.tasks" :isLoading="purchaseStore.isLoading"
                @file-change="purchaseStore.handleFileChange" @remove-file="purchaseStore.removeFile"
                @upload="purchaseStore.uploadRequirements" />
        </Modal>
    </div>
</template>
