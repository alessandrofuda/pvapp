<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import { useTrans } from '@/composables/trans.js';
    import OperatorForm from "@/Components/OperatorForm.vue";

    defineProps({ areas_opts: Object })

    const submit = (form) => {
        form.post(route('operator_store_by_admin'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Create Operator" />
        <div class="text-lg text-center my-3">Crea un nuovo Operatore</div>

        <operator-form submitButtonLabel="Create Operator" :areas_opts="areas_opts" @formSubmitted="submit">
            <!-- not shared fields, slot -->
            <Link
                :href="route('operators')"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                {{ useTrans('Back to Operators') }}
            </Link>
        </operator-form>
    </AuthenticatedLayout>
</template>

