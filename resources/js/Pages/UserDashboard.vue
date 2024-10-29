<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
</script>

<template>
    <Head title="User Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Dashboard</h2>
        </template>

        <template #nav-menu>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <NavLink :href="route('user_dashboard')" :active="route().current('user_dashboard')">
                    User Dashboard
                </NavLink>
            </div>
        </template>

        <template #mobile-nav-menu-links>
            <ResponsiveNavLink :href="route('user_dashboard')" :active="route().current('user_dashboard')">
                Dashboard
            </ResponsiveNavLink>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div>You're logged in User Dashboard!</div>
                        <div class="mt-4">User: {{ $page.props.auth.user.name }}</div>
                        <div>Role: {{ $page.props.auth.user.roles[0].name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
