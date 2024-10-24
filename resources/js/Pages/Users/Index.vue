<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SearchFilter from '@/Components/SearchFilter.vue';
import Pagination from '@/Components/Pagination.vue';
import Icon from '@/Components/Icon.vue';
import { reactive, watch } from 'vue';
import { pickBy, throttle } from 'lodash';

const props = defineProps({
    users: Object
});

const filters = {
    search: '',
};

const form = reactive({
    search: filters.search,
});

watch(
    form,
    throttle(() => {
        router.get('/users', pickBy(form), { preserveState: true });
    }, 150),
    { deep: true }
);
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex items-center justify-between mb-6 p-6">
                        <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset" />
                        <Link class="btn-indigo" href="/users/create">
                            <span>Create</span>
                            <span class="hidden md:inline">&nbsp;User</span>
                        </Link>
                    </div>

                    <div class="bg-white rounded-md shadow overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="text-left font-bold">
                                    <th class="pb-4 pt-6 px-6">Name</th>
                                    <th class="pb-4 pt-6 px-6">E-mail</th>
                                    <th class="pb-4 pt-6 px-6" colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                                    <td class="border-t">
                                        <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/users/${user.id}/edit`">
                                            {{ user.name }}
                                            <Icon v-if="user.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                                        </Link>
                                    </td>
                                    <td class="border-t">
                                        <Link class="flex items-center px-6 py-4" :href="`/users/${user.id}/edit`" tabindex="-1">
                                            {{ user.email }}
                                        </Link>
                                    </td>
                                    <td class="w-px border-t">
                                        <Link class="flex items-center px-4" :href="`/users/${user.id}/edit`" tabindex="-1">
                                            <Icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="users.length === 0">
                                    <td class="px-6 py-4 border-t" colspan="4">No users found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <Pagination class="mt-6 p-6" :links="users.links" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
