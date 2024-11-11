<script setup>
    import AuthenticationLayout from '@/Layouts/AuthenticationLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import { useTrans } from '@/Composables/trans';
    import OperatorForm from "@/Components/OperatorForm.vue";

    defineProps({ areas_opts: Object })

    const submit = (form) => {
        form.post(route('operator_register'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };
</script>
<template>
    <AuthenticationLayout>
        <Head title="Register Operator" />
        <div class="text-lg text-center my-3">Iscriviti al Servizio Installatori</div>

        <operator-form submitButtonLabel="Sign Up" :areas_opts="areas_opts" @formSubmitted="submit">
            <!-- not shared fields, slot -->
            <Link
                :href="route('login')"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                {{ useTrans('Already registered?') }}
            </Link>
        </operator-form>
    </AuthenticationLayout>
</template>
