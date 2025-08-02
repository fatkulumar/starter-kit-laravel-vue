import type { User } from "@/types";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { useForm } from "@inertiajs/vue3";

export type UserListResponse = ApiResponse<PaginatedData<User>>

interface UserForm {
    id: string,
    name: string,
    email: string,
    password: string,
    role: string,
    [key: string]: any
    photo: File | null | undefined
}

enum Roles {
    ADMIN = 'admin',
    MEMBER = 'member',
}

export const useUserStore = defineStore('user-admin', {
    state: (): {
        users: User[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        userCache: Map<string, UserListResponse>,
        showModal: boolean,
        form: UserForm,
        previewPhoto: string,
        checkedAll: boolean,
        selectedIds: string[]
    } => ({
        users: [] as User[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        userCache: new Map<string, UserListResponse>(),
        showModal: false,
        form: useForm<UserForm>({
            id: '',
            name: '',
            email: '',
            password: '@Haha123',
            role: '',
            photo: null
        }),
        previewPhoto: '',
        checkedAll: false,
        selectedIds: []
    }),
    getters: {
        roleOptions(): { label: string; value: Roles }[] {
            return Object.values(Roles).map((value) => ({
                label: value.charAt(0).toUpperCase() + value.slice(1),
                value,
            }))
        }
    },
    actions: {
        async fetchUsers(page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;

            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_user_admin_${searchQuery}` : `page_${page}`;

            try {
                if (this.userCache.has(cacheKey)) {
                    const cached = this.userCache.get(cacheKey)!;

                    if (Array.isArray(cached.data)) {
                        this.users = cached.data;
                        this.pagination = null;
                    } else {
                        this.users = cached.data.data;
                        this.pagination = {
                            current_page: cached.data.current_page,
                            per_page: cached.data.per_page,
                            total: cached.data.total,
                            last_page: cached.data.last_page,
                            next_page_url: cached.data.next_page_url,
                            prev_page_url: cached.data.prev_page_url,
                            from: cached.data.from,
                            to: cached.data.to,
                            path: cached.data.path,
                            links: cached.data.links,
                        };
                    }

                    this.page = page;
                    return;
                }

                const url = isSearching
                    ? `/apiadmin/dashboard/user?search=${encodeURIComponent(searchQuery)}`
                    : `/apiadmin/dashboard/user?page=${page}`;

                const response = await axios.get<UserListResponse>(url);
                const userData = response.data.data;

                if (Array.isArray(userData)) {
                    this.users = userData;
                    this.pagination = null;
                } else {
                    this.users = userData.data;
                    this.pagination = {
                        current_page: userData.current_page,
                        per_page: userData.per_page,
                        total: userData.total,
                        last_page: userData.last_page,
                        next_page_url: userData.next_page_url,
                        prev_page_url: userData.prev_page_url,
                        from: userData.from,
                        to: userData.to,
                        path: userData.path,
                        links: userData.links,
                    };
                }

                this.page = page;

                this.userCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data user' };
            } finally {
                this.isLoading = false;
            }
        },

        async handleSave(): Promise<void> {
            this.isLoading = true;

            const isEdit = !!this.form.id;
            const url = isEdit
                ? `/apiadmin/dashboard/user/${this.form.id}`
                : `/apiadmin/dashboard/user`;

            const formData = new FormData();
            if (isEdit) {
                formData.append('_method', 'PUT');
                formData.append('id', this.form.id);
            }

            formData.append('name', this.form.name);
            formData.append('email', this.form.email);
            formData.append('password', this.form.password ?? '');
            formData.append('role', this.form.role);

            if (this.form.photo) {
                formData.append('photo', this.form.photo);
            }

            try {
                const response = await axios.post<ApiResponse<User>>(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response?.status === 200) {
                    const updatedUser = response.data.data;

                    if (isEdit) {
                        this.users = this.users.map(u => u.id === updatedUser.id ? updatedUser : u);
                    } else {
                        this.users.unshift(updatedUser);
                    }

                    this.hanldeResetForm();
                    this.showModal = false;
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal submit data user' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        handleEdit(item: User): void {
            this.form.id = item.id;
            this.form.name = item.name;
            this.form.email = item.email;
            this.form.role = item.role;
            this.previewPhoto = item.profile?.photo_url ?? '';
            this.showModal = true;
        },

        async handleDelete(id: string) {
            this.isLoading = true;
            const method = 'delete';
            const url = `/apiadmin/dashboard/user/${id}`
            try {
                const response = await axios.delete<ApiResponse<string>>(url);
                if (response?.status === 200) {
                    this.users = this.users.filter(user => user.id !== id);
                    this.showModal = false;
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal delete data user' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        async handleConfirmDelete(item: User): Promise<void> {
            const konfirm = confirm(`Hapus ${item.name}?`)
            if (konfirm) {
                await this.handleDelete(item.id);
            }
        },

        handlePageChange(page: number): void {
            this.fetchUsers(page);
        },

        async handleSearch(): Promise<void> {
            await this.fetchUsers(this.page, this.searchQuery);
        },

        hanldeResetForm(): void {
            this.showModal = false;
            this.form.id = '';
            this.form.email = '';
            this.form.name = '';
            this.form.role = '';
            this.form.photo = null;
            this.previewPhoto = '';
        },

        handleFileChange(event: Event): void {
            const target = event.target as HTMLInputElement;
            if (target.files && target.files[0]) {
                this.form.photo = target.files[0];
                const reader = new FileReader();
                reader.onload = () => {
                    this.previewPhoto = reader.result as string;
                }
                reader.readAsDataURL(target.files[0]);
            }
        },

        handleCloseModal(): void {
            this.showModal = false;
            this.hanldeResetForm();
        },

        toggleSelectAll(users: User[]): void {
            if (this.checkedAll) {
                this.selectedIds = users.map(u => u.id);
            } else {
                this.selectedIds = [];
            }
        },
        toggleSelectOne(userId: string): void {
            if (this.selectedIds.includes(userId)) {
                this.selectedIds = this.selectedIds.filter(id => id !== userId);
            } else {
                this.selectedIds.push(userId);
            }
            this.checkedAll = false;
        },

        syncCheckedAll(users: User[]): void {
            this.checkedAll = users.length > 0 && users.every(user => this.selectedIds.includes(user.id));
        },

        async handleDeleteAll() {
            this.isLoading = true;
            const method = 'post';
            const url = `/apiadmin/dashboard/apiadmin/dashboard/user/delete-all `
            const form = {
                ids: this.selectedIds
            }
            try {
                const response = await axios.post<ApiResponse<User>>(url, form, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                if (response?.status === 200) {
                    let deleteIds: string[] = [];
                    const data = response.data.data;

                    if (typeof data === 'string') {
                        deleteIds = [data];
                    } else if (Array.isArray(data)) {
                        deleteIds = data;
                    } else {
                        return;
                    }
                    this.users = this.users.filter(user => !deleteIds.includes(user.id));
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal delete data user' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        async hanldeConfirmDeleteAll(): Promise<void> {
            const konfirm = confirm(`Hapus?`)
            if (konfirm) {
                await this.handleDeleteAll();
            }
        }
    }
});