<script setup>
    import { Head, Link } from '@inertiajs/vue3';
    import { useTrans } from '@/composables/trans.js';
    import OperatorForm from "@/Components/OperatorForm.vue";
    import AdminAuthenticatedLayout from "@/Layouts/AdminAuthenticatedLayout.vue";

    defineProps({ areas_opts: Object })

    const submit = (form) => {
        form.post(route('operator_store_by_admin'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };
</script>
<template>
    <Head title="Operator" />
    <AdminAuthenticatedLayout class="m-3 p-5">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registra un nuovo operatore</h2>
        </template>

        <operator-form submitButtonLabel="Create Operator" :areas_opts="areas_opts" @formSubmitted="submit" class="max-w-7xl mx-auto my-4 py-4 px-4 sm:px-6 lg:px-8">
            <!-- not shared fields, slot -->
            <Link
                :href="route('operators')"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                {{ useTrans('Back to Operators') }}
            </Link>
        </operator-form>
    </AdminAuthenticatedLayout>
</template>

