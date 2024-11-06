<script setup>
    import {useTrans} from "@/composables/trans.js";
    import TextInput from "@/Components/TextInput.vue";
    import TextArea from "@/Components/TextArea.vue";
    import InputError from "@/Components/InputError.vue";
    import InputLabel from "@/Components/InputLabel.vue";
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import VueMultiselect from "vue-multiselect";
    import {useForm} from "@inertiajs/vue3";

    const props = defineProps({
        lead: Object,
        towns_opts: Object,
        submitButtonLabel: String
    })

    const form = useForm({
        id: props.lead?.id || null,
        name: props.lead?.name || '',
        lastname: props.lead?.lastname || '',
        email: props.lead?.email || '',
        phone: props.lead?.phone || '',
        town: props.lead?.area ? getTownObject(props.lead.area) : null,
        description: props.lead?.description || ''
    });

    const emit = defineEmits(['formSubmitted']);

    const submit = () => {
        emit('formSubmitted', form)  // Emit form data to parent component
    };

    function townWithProvAndReg({town, prov, region}) {
        return `${town}, ${prov}, ${region}`
    }
    function getTownObject(areaObject) {
        return { id: areaObject.id, region: areaObject.region_name, prov: areaObject.province_code, town: areaObject.town }
    }
</script>

<!-- SharedForm.vue -->
<template>
    <form @submit.prevent="submit">
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
                type="text"
                class="mt-1 block w-full"
                v-model="form.email"
                autocomplete="name"
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
                {{ submitButtonLabel || useTrans('Send Request') }}
            </PrimaryButton>
        </div>
    </form>
</template>

<!-- Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
