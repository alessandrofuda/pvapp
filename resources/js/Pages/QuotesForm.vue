<script setup>

import GuestLayout from "@/Layouts/GuestLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import {useTrans} from "../composables/trans.js";
import VueMultiselect from "vue-multiselect";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextArea from "@/Components/TextArea.vue";


defineProps({ towns_opts: Object })

const form = useForm({
    name: null,
    lastname: null,
    email: null,
    phone: null,
    town: null,
    description: null
});

const submit = () => {
    form.post(route('save_lead'), {
        onFinish: (resp) => {
            console.log(resp)
            form.reset() // todo not works
        },
    });
};

function townWithProvAndReg({town, prov, region}) {
    return `${town}, ${prov}, ${region}`
}

</script>
<template>
    <guestLayout>
        <Head title="Preventivi" />
        <div class="text-lg text-center my-3">{{useTrans('Ask for a quote')}}</div>
        <form @submit.prevent="submit">
            <div class="my-5">
                <!--InputLabel for="name" :value="useTrans('Name')" /-->
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Nome (o eventuale Ragione Sociale)"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div class="my-5">
                <!--InputLabel for="lastname" :value="useTrans('Last Name') +' ('+useTrans('Not mandatory')+')'" /-->
                <TextInput
                    id="lastname"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.lastname"
                    autofocus
                    autocomplete="lastname"
                    placeholder="Cognome (opzionale)"
                />
                <InputError class="mt-2" :message="form.errors.lastname" />
            </div>
            <div class="my-5">
                <!--InputLabel for="email" value="Email" /-->
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="E-mail"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div class="my-5">
                <!--InputLabel for="phone" :value="useTrans('Phone')" /-->
                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    required
                    autocomplete="phone"
                    placeholder="Telefono ('335 1234567', '+39 06 1234567')"
                />
                <InputError class="mt-2" :message="form.errors.phone" />
            </div>
            <div class="my-5 text-center py-4">
                <InputLabel  class="mx-3 mb-1 px-4 text-base" for="town" value="Seleziona il Comune di installazione" />
                <vue-multiselect
                    v-model="form.town"
                    :options="towns_opts"
                    :multiple="false"
                    placeholder="Digita e seleziona il Comune di installazione"
                    label="town"
                    track-by="town"
                    :custom-label="townWithProvAndReg">
                </vue-multiselect>
                <InputError class="mt-2" :message="form.errors.town" />
            </div>
            <div class="my-5 pt-3 text-center">
                <InputLabel for="description" :value="useTrans('Optional details')" />
                <TextArea
                    id="description"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    required
                    autocomplete=""
                    placeholder="Scrivi eventuali dettagli. Non inserire qui dati sensibili: Cognome, Telefono, Email, indirizzi, ecc.."
                    rows="5"
                />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>
            <div class="items-center text-center my-6 pt-4">
                <PrimaryButton class="!text-base" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ useTrans('Send Request') }}
                </PrimaryButton>
            </div>
        </form>
    </guestLayout>
</template>
<!-- Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
