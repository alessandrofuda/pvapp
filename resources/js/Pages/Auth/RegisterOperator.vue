<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import VueMultiselect from 'vue-multiselect'

defineProps({ areas_opts: Object })

const form = useForm({
    name: '',
    email: '',
    phone: '',
    areas: null,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('operator_register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

function nameWithType({name, type}) {
    return (type === 'provincia') ? `${name} (${type})` : `${name}`
}

</script>

<template>
    <GuestLayout>
        <Head title="Register Operator" />
        <div class="text-lg text-center my-3">Iscriviti al Servizio Installatori</div>
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nome" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div class="mt-4">
                <InputLabel for="phone" value="Telefono" />
                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    required
                    autocomplete="phone"
                />
                <InputError class="mt-2" :message="form.errors.phone" />
            </div>
            <div class="mt-4 text-center my-4 py-4">
                <InputLabel  class="mx-3 my-6 px-4 text-base" for="areas" value="Seleziona una o più Aree Geografiche da cui ricevere le richieste di Preventivo" />
                <vue-multiselect
                    v-model="form.areas"
                    :options="areas_opts"
                    :multiple="true"
                    placeholder="Cerca una o più aree geografiche (Provincia o Regione)"
                    label="name"
                    track-by="name"
                    :custom-label="nameWithType">
                </vue-multiselect>
                <div class="text-sm/relaxed text-gray-400">Cerca per Provincia - Regione - 'Tutta Italia' o Estero</div>
                <InputError class="mt-2" :message="form.errors.areas" />
            </div>
            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Ridigita Password" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end my-6 pt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Già registrato?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Iscriviti
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<!-- Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
