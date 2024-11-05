<script setup>
    import GuestLayout from "@/Layouts/GuestLayout.vue";
    import {Head} from "@inertiajs/vue3";
    import {useTrans} from "../composables/trans.js";
    import AlertComponent from "@/Components/AlertComponent.vue";
    import {ref} from "vue";
    import LeadForm from "@/Components/LeadForm.vue";

    const props = defineProps({ towns_opts: Object })

    let successMessage = ref('')

    //onMounted(() => {
        // console.log('mounted component!')
    //})

    const submit = (form) => {
        form.post(route('save_quotation_req'), {  // <-- inertia.js
            preserveScroll: true,
            onSuccess: () => {
                successMessage.value = 'La tua richiesta è stata inviata correttamente. Ti ricontatteremo per ulteriori dettagli.'
                form.reset()
            },
            onError: () => console.log('error'),
            // onFinish: () => console.log('finish')
        });
    };

</script>
<template>
    <guestLayout>
        <Head title="Preventivi" />
        <div class="text-lg text-center my-3">{{useTrans('Ask for a quote')}}</div>

        <AlertComponent color="green" :message="successMessage"/>

        <lead-form :towns_opts="towns_opts" @formSubmitted="submit"></lead-form>

        <!--form @submit.prevent="submit">
            <div class="my-5">
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    autofocus
                    autocomplete="name"
                    placeholder="Nome (o eventuale Ragione Sociale)"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div class="my-5">
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
                <TextInput
                    id="email"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    autocomplete="name"
                    placeholder="E-mail"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div class="my-5">
                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone"
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
        </form-->
    </guestLayout>
</template>

