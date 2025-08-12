import { ApiResponse } from "@/types/ApiResponse";
import { Purchase } from "@/types/Purchase";
import { showError, showSuccess } from "@/utils/alert";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import { useTryoutStore } from "./tryoutStore";

export interface TaskItem {
    label: string;
    file: File | null;
    preview: string;
}

export const usePurchaseStore = defineStore('purchase', {
    state: (): {
        modalUploadRequirements: boolean,
        tasks: TaskItem[],
        requirementFiles: Record<string, File | null>,
        requirementPreviews: Record<string, string>,
        isLoading: boolean,
        error: Record<string, any> | null,
    } => ({
        modalUploadRequirements: false,
        tasks: [
            { label: 'Follow Instagram', file: null, preview: '' },
            // { label: 'Komen dan tag 10 Teman postingan Instagram', file: null, preview: '' },
            // { label: 'Share postingan ini di story kamu dan tag instagram', file: null, preview: '' },
            // { label: 'Share postingan instagram ke 3 grup belajar kamu', file: null, preview: '' }
        ],
        requirementFiles: {},
        requirementPreviews: {},
        isLoading: false,
        error: null
    }),
    actions: {
        // change file
        handleFileChange(e: Event, index: number) {
            const target = e.target as HTMLInputElement;
            if (target.files && target.files[0]) {
                const file = target.files[0];
                this.tasks[index].file = file;
                this.tasks[index].preview = URL.createObjectURL(file);
            }
        },

        // remove image form form
        removeFile(index: number) {
            this.tasks[index].file = null;
            this.tasks[index].preview = '';
        },

        // action upload
        async uploadRequirements() {
            const tryoutStore = useTryoutStore();
            this.isLoading = true;
            try {
                const formData = new FormData();

                // sertakan selectedIds
                tryoutStore.selectedIds.forEach((id, idx) => {
                    formData.append(`tryout_id[${idx}]`, id);
                });

                formData.append('amount', String(tryoutStore.amount));

                // sertakan label + file (jika ada)
                this.tasks.forEach((task, i) => {
                    formData.append(`tasks[${i}][label]`, task.label);
                    if (task.file) {
                        formData.append(`tasks[${i}][file]`, task.file);
                    }
                });

                // POST pakai form-data langsung
                const response = await axios.post<ApiResponse<Purchase>>('/api/student/purchase', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response?.status === 200) {
                    showSuccess(response.data.message);
                    this.error = null;
                    this.modalUploadRequirements = false;
                    tryoutStore.modalGetTryout = false;
                    tryoutStore.selectedIds = [];
                    tryoutStore.amount = 0;

                    this.tasks = this.tasks.map(task => ({
                        ...task,
                        file: null,
                        preview: '',
                    }));

                    tryoutStore.deleteCache();
                }

            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                    const errors = err.response.data.errors || {}
                    showError(errors);
                } else {
                    this.error = err?.response?.data || { message: 'Gagal submit data pembelian' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        // close modal upload requirement
        async handleCloseModalUploadRequirements(): Promise<void> {
            const tryoutStore = useTryoutStore();
            tryoutStore.modalGetTryout = true;
            this.modalUploadRequirements = false;
            this.requirementFiles = {};          // key = nama requirement, value = File/null
            this.requirementPreviews = {};

            this.tasks = this.tasks.map(task => ({
                ...task,
                file: null,
                preview: ''
            }));
        },
    }
});
