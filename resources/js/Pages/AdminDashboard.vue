<script setup>
import { Head } from '@inertiajs/vue3';
import AdminAuthenticatedLayout from "@/Layouts/AdminAuthenticatedLayout.vue";
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div>You're logged in Admin Dashboard!</div>
                        <div class="mt-4">User: {{ $page.props.auth.user.name }}</div>
                        <div>Role: {{ $page.props.auth.user.roles[0].name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AdminAuthenticatedLayout>
</template>
