<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import { reactive, ref } from 'vue';
import axios from '@/lib/axios'

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage();
const user = page.props.auth.user as User;
const error = ref<Record<string, any> | null>(null);

const form = useForm<{
    name: string;
    email: string;
    photo: string | File | null;
}>({
    name: user.name,
    email: user.email,
    photo: null
});

let recentlySuccessful = ref<boolean>(false);

const submit = async () => {

    const formData = new FormData();
    formData.append('_method', 'PATCH');
    formData.append('name', form.name);
    formData.append('email', form.email);
    formData.append('photo', form.photo ?? '');

    try {
        const response = await axios.post(route('profile.update'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response?.status === 200) {
            recentlySuccessful.value = true;
            setTimeout(() => {
                recentlySuccessful.value = false;
            }, 1000);
        }

    } catch (err: any) {
        console.log(err)
        if (err?.response?.status === 422) {
            error.value = err.response.data.errors || { message: 'Data tidak valid' };
        } else {
            error.value = err?.response?.data || { message: 'Gagal submit data user' };
        }
    }
};

const previewPhoto = ref<string | null>(user.profile?.photo_url ?? null)

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.photo = target.files[0];
        const reader = new FileReader();
        reader.onload = () => {
            previewPhoto.value = reader.result as string;
        }
        reader.readAsDataURL(target.files[0]);
    }
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form @submit.prevent="submit" class="space-y-6">
                     <div class="grid gap-2">
                        <img v-if="previewPhoto" :src="previewPhoto" alt="Preview" class="max-w-xs rounded shadow w-20" />
                        <Label for="photo">Foto</Label>
                        <Input id="photo" type="file" autofocus :tabindex="1" autocomplete="photo"
                            @change="handleFileChange" />
                        <InputError v-if="error?.photo" :message="error?.photo[0]" />

                    </div>

                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                        <InputError v-if="error?.name" class="mt-2" :message="error?.name[0]" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError v-if="error?.email" class="mt-2" :message="error?.email[0]" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
