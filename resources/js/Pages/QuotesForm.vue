<script setup>

import GuestLayout from "@/Layouts/GuestLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import {useTrans} from "../composables/trans.js";
import VueMultiselect from "vue-multiselect";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

defineProps({ towns_opts: Object })

const form = useForm({
    name: '',
    lastname: '',
    email: '',
    phone: '',
    town: null,
});

const submit = () => {
    alert('todo')
    //form.post(route(''), {
        // onFinish: () => form.reset('password', 'password_confirmation'),
    //});
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
            <div>
                <InputLabel for="name" :value="useTrans('Name')" />
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
                <InputLabel for="lastname" :value="useTrans('Last Name') +' ('+useTrans('Not mandatory')+')'" />
                <TextInput
                    id="lastname"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.lastname"
                    autofocus
                    autocomplete="lastname"
                />
                <InputError class="mt-2" :message="form.errors.lastname" />
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
                <InputLabel for="phone" :value="useTrans('Phone')" />
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
                <InputLabel  class="mx-3 my-6 px-4 text-base" for="town" value="Seleziona il Comune di installazione" />
                <div class="text-sm/relaxed text-gray-400">Inserisci Comune di installazione</div>
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

        </form>



    </guestLayout>
</template>
<!-- Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
