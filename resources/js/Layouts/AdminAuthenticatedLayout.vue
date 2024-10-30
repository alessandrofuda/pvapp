<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import NavLink from "@/Components/NavLink.vue";
</script>
<template>
    <AuthenticatedLayout>
        <template #nav-menu>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <NavLink :href="route('admin_dashboard')" :active="route().current('admin_dashboard')">
                    Admin Dashboard
                </NavLink>
                <NavLink :href="route('operators')" :active="route().current('operators')">
                    Operators
                </NavLink>
                <NavLink :href="route('leads')" :active="route().current('leads')">
                    Leads
                </NavLink>
                <NavLink :href="route('users')" :active="route().current('users')">
                    Users
                </NavLink>
                <NavLink :href="route('transactions')" :active="route().current('transactions')">
                    Transactions
                </NavLink>
            </div>
        </template>

        <template #mobile-nav-menu-links>
            <ResponsiveNavLink :href="route('admin_dashboard')" :active="route().current('admin_dashboard')">
                Dashboard
            </ResponsiveNavLink>
        </template>

        <template #header>
            <slot name="header" />
        </template>

        <slot />

    </AuthenticatedLayout>
</template>
