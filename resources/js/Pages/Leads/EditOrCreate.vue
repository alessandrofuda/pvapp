<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import { useTrans } from '@/Composables/trans.js';
import AdminAuthenticatedLayout from "@/Layouts/AdminAuthenticatedLayout.vue";
import {computed, nextTick, reactive, ref} from "vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import LeadForm from "@/Components/LeadForm.vue";

const props = defineProps({
    lead: Object,
    towns_opts: Object
})

const leadProfile = useForm({
    id: props.lead?.id,
});

const submit = (form) => {
    if(form.id) {
        form.put(route('edit_lead', {lead: form.id}), {
            // onFinish: () =>
        })
    }else{
        form.post(route('save_lead'), {
            // onFinish: () =>,
        });
    }
};

const submitBtnLabel = computed(() => (props.lead ? 'Aggiorna' : 'Salva nuovo') + ' Lead')
const confirmingLeadDeletion = ref(false);


const confirmLeadDeletion = () => {
    confirmingLeadDeletion.value = true;
};

const closeModal = () => {
    confirmingLeadDeletion.value = false;
};

const deleteLead = () => {
    leadProfile.delete(route('delete_lead', {lead: leadProfile.id}), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: (e) => {
            // console.error(e)
        }
        // onFinish: () =>
    });
};

</script>
<template>
    <Head title="Lead" />
    <AdminAuthenticatedLayout class="m-3 p-5">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{useTrans('Add manually a new Lead')}}</h2>
        </template>
        <lead-form
            :submitButtonLabel=submitBtnLabel
            :lead="lead"
            :towns_opts="towns_opts"
            @formSubmitted="submit"
            class="max-w-7xl mx-auto my-4 py-4 px-4 sm:px-6 lg:px-8"
        >
            <!-- not shared fields, slot -->
            <Link
                :href="route('leads')"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                {{ useTrans('Back to Leads') }}
            </Link>
        </lead-form>

        <div v-if="lead" class="max-w-7xl mx-auto my-4 pb-12 py-4 px-4 sm:px-6 lg:px-8">
            <DangerButton @click="confirmLeadDeletion">{{ useTrans('Delete Lead') }}</DangerButton>

            <Modal :show="confirmingLeadDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Are you sure you want to delete this Lead?
                    </h2>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                        <DangerButton
                            class="ms-3"
                            :class="{ 'opacity-25': leadProfile.processing }"
                            :disabled="leadProfile.processing"
                            @click="deleteLead"
                        >
                            {{ useTrans('Delete Lead') }}
                        </DangerButton>
                    </div>
                </div>
            </Modal>

        </div>
    </AdminAuthenticatedLayout>
</template>

