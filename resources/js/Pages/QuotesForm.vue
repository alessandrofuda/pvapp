<script setup>
    import GuestLayout from "@/Layouts/GuestLayout.vue";
    import {Head} from "@inertiajs/vue3";
    import {useTrans} from "@/Composables/trans.js";
    import AlertComponent from "@/Components/AlertComponent.vue";
    import {ref} from "vue";
    import LeadForm from "@/Components/LeadForm.vue";

    const props = defineProps({ towns_opts: Object })

    let successMessage = ref('')

    const submit = (form) => {
        form.post(route('save_quotation_req'), {  // <-- inertia.js
            preserveScroll: true,
            onSuccess: () => {
                successMessage.value = 'La tua richiesta è stata inviata correttamente. Ti ricontatteremo per ulteriori dettagli.'
                form.reset()
            },
            onError: () => console.log('Error see logs for details'),
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

    </guestLayout>
</template>

