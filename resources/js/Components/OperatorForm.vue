<script setup>
    import {useTrans} from "@/composables/trans.js";
    import InputError from "@/Components/InputError.vue";
    import InputLabel from "@/Components/InputLabel.vue";
    import {useForm} from "@inertiajs/vue3";
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import TextInput from "@/Components/TextInput.vue";
    import VueMultiselect from "vue-multiselect";

    const props = defineProps({
        operator: Object,
        areas_opts: Object,
        submitButtonLabel: String,
        // userRole: String
    })

    // const operator = usePage().props.operator; // inertia.js method

    const form = useForm({
        id: props.operator?.id || null,
        name: props.operator?.name || '',
        email: props.operator?.email || '',
        phone: props.operator?.phone || '',
        areas: [...props.operator?.regions || [], ...props.operator?.provinces || []],
        password: '',
        password_confirmation: '',
    });

    const emit = defineEmits(['formSubmitted']);

    const submit = () => {
        emit('formSubmitted', form)  // Emit form data to parent component
    };

    function nameWithType({name, type}) {
        return (type === 'provincia') ? `${name} (${type})` : `${name}`
    }
</script>
<!-- SharedForm.vue -->
<template>
    <form @submit.prevent="submit">
        <div>
            <InputLabel for="name" :value="useTrans('Name')" />
            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                autofocus
                autocomplete="name"
            />
            <InputError class="mt-2" :message="form.errors.name" />
        </div>
        <div class="mt-4">
            <InputLabel for="email" value="Email" />
            <TextInput
                id="email"
                type="text"
                class="mt-1 block w-full"
                v-model="form.email"
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
                autocomplete="phone"
            />
            <InputError class="mt-2" :message="form.errors.phone" />
        </div>
        <div class="mt-4 text-center my-4 py-4">
            <InputLabel  class="mx-3 my-6 px-4 text-base" for="areas" value="Seleziona una o più Aree Geografiche da cui ricevere le richieste di Preventivo" />
            <div class="text-sm/relaxed text-gray-400">Cerca Provincia - Regione - Tutta Italia - Estero</div>
            <vue-multiselect
                v-model="form.areas"
                :options="areas_opts"
                :multiple="true"
                placeholder="Cerca una o più aree geografiche (Provincia o Regione)"
                label="name"
                track-by="name"
                :custom-label="nameWithType">
            </vue-multiselect>
            <InputError class="mt-2" :message="form.errors.areas" />
        </div>


        <div v-if="!form.id">
            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" :value="useTrans('Password Confirmation')" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>
        </div>
        <div class="flex items-center justify-end my-6 pt-4">

            <!-- Slot for additional unique fields -->
            <slot></slot>

            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ useTrans(submitButtonLabel) }}
            </PrimaryButton>
        </div>
    </form>
</template>

<!-- Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
