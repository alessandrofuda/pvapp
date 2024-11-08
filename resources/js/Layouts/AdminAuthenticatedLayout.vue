<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import NavLink from "@/Components/NavLink.vue";
import {useTrans} from "../Composables/trans.js";
</script>
<template>
    <AuthenticatedLayout>
        <template #nav-menu>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <NavLink :href="route('admin_dashboard')" :active="route().current('admin_dashboard')">
                    Admin Dashboard
                </NavLink>
                <NavLink :href="route('operators')" :active="route().current('operators')">
                    {{ useTrans('Operators') }}
                </NavLink>
                <NavLink :href="route('leads')" :active="route().current('leads')">
                    Leads
                </NavLink>
                <NavLink :href="route('users')" :active="route().current('users')">
                    {{ useTrans('Users') }}
                </NavLink>
                <NavLink :href="route('transactions')" :active="route().current('transactions')">
                    {{ useTrans('Transactions') }}
                </NavLink>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <div class="inline-flex items-center ml-6 px-1 pt-1 text-sm text-red-500">Role: {{ $page.props.auth.user.roles[0].name }}</div>
            </div>
        </template>

        <template #mobile-nav-menu-links>
            <ResponsiveNavLink :href="route('admin_dashboard')" :active="route().current('admin_dashboard')">
                Dashboard
            </ResponsiveNavLink>
            <ResponsiveNavLink>
                todo
            </ResponsiveNavLink>
        </template>

        <template #header>
            <slot name="header" />
        </template>

        <slot />

    </AuthenticatedLayout>
</template>
