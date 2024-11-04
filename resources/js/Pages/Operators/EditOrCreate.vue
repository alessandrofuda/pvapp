<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
    import { useTrans } from '@/composables/trans.js';
    import OperatorForm from "@/Components/OperatorForm.vue";
    import AdminAuthenticatedLayout from "@/Layouts/AdminAuthenticatedLayout.vue";
    import {computed, nextTick, ref} from "vue";
    import DangerButton from "@/Components/DangerButton.vue";
    import SecondaryButton from "@/Components/SecondaryButton.vue";
    import Modal from "@/Components/Modal.vue";

    const props = defineProps({
        operator: Object,
        areas_opts: Object
    })

    const operatorProfile = useForm({
        id: props.operator.id,
    });

    const submit = (form) => {
        if(form.id) {
            form.put(route('edit_operator', {operator: form.id}), {
                // onFinish: () =>
            })
        }else{
            form.post(route('save_operator'), {
                onFinish: () => form.reset('password', 'password_confirmation'),
            });
        }
    };

    const submitBtnLabel = computed(() => props.operator ? 'Aggiorna' : 'Salva')
    const confirmingProfileDeletion = ref(false);


    const confirmProfileDeletion = () => {
        confirmingProfileDeletion.value = true;
    };

    const closeModal = () => {
        confirmingProfileDeletion.value = false;
    };

    const deleteOperator = () => {
        operatorProfile.delete(route('delete_operator', {operator: operatorProfile.id}), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            // onFinish: () => // todo redirect operatorProfile.reset(),
        });
    };


</script>
<template>
    <Head title="Operator" />
    <AdminAuthenticatedLayout class="m-3 p-5">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registra un nuovo operatore</h2>
        </template>

        <operator-form
            :submitButtonLabel=submitBtnLabel
            :operator="operator"
            :areas_opts="areas_opts"
            @formSubmitted="submit"
            class="max-w-7xl mx-auto my-4 py-4 px-4 sm:px-6 lg:px-8"
        >
            <!-- not shared fields, slot -->
            <Link
                :href="route('operators')"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                {{ useTrans('Back to Operators') }}
            </Link>
        </operator-form>

        <div class="max-w-7xl mx-auto my-4 pb-12 py-4 px-4 sm:px-6 lg:px-8">
            <DangerButton @click="confirmProfileDeletion">{{ useTrans('Delete Profile') }}</DangerButton>

            <Modal :show="confirmingProfileDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Are you sure you want to delete this Operator Profile?
                    </h2>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                        <DangerButton
                            class="ms-3"
                            :class="{ 'opacity-25': operatorProfile.processing }"
                            :disabled="operatorProfile.processing"
                            @click="deleteOperator"
                        >
                            {{ useTrans('Delete Profile') }}
                        </DangerButton>
                    </div>
                </div>
            </Modal>

        </div>
    </AdminAuthenticatedLayout>
</template>

