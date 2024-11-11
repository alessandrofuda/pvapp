<script setup>
import { computed } from 'vue';
import AuthenticationLayout from '@/Layouts/AuthenticationLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <AuthenticationLayout>
        <Head title="Email Verification" />

        <div class="mb-4 text-sm text-gray-600">
<!--            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link
            we just emailed to you? If you didn't receive the email, we will gladly send you another.-->
            Grazie per esserti iscritto! Prima di cominciare, verifica la tua e-mail cliccando sul link che ti abbiamo inviato.
            Se non ricevi la mail, possiamo inviartene un'altra.

        </div>

        <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent">
<!--            A new verification link has been sent to the email address you provided during registration.-->
            Ti abbiamo inviato un nuovo link di verifica alla mail fornita durante la registrazione.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
<!--                    Resend Verification Email-->
                    Reinvia la Email di veririfca
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >Log Out</Link
                >
            </div>
        </form>
    </AuthenticationLayout>
</template>
