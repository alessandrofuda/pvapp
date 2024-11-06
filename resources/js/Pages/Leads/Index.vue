<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import SearchFilter from "@/Components/SearchFilter.vue";
import {reactive, watch} from "vue";
import Pagination from "@/Components/Pagination.vue";
import Icon from "@/Components/Icon.vue";
import {pickBy, throttle} from "lodash";
import AdminAuthenticatedLayout from "@/Layouts/AdminAuthenticatedLayout.vue";
import {useTrans} from "../../composables/trans.js";

const props = defineProps({
    leads: Array
})
const form = reactive({
    search: null,
});
const reset = () => {
    form.search = null
}

const editLeadLink = (leadId) => { return `/lead/${leadId}` }

// Watch the form object deeply with throttling
watch(
    form,
    throttle(() => {
        // Use router to send a request
        router.get('/leads', pickBy(form), { preserveState: true });
    }, 150),
    { deep: true }
);
</script>
<template>
    <Head title="Leads" />
    <AdminAuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{useTrans('Leads')}}</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex items-center justify-between mb-6 p-6">
                        <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset"></search-filter>
                        <Link class="btn-indigo" href="/lead">
                            <span>{{useTrans('Add')}}</span>
                            <span class="hidden md:inline">&nbsp{{useTrans('New Lead')}}</span>
                        </Link>
                    </div>
                    <div class="counter text-sm/relaxed px-5 text-gray-500">{{useTrans('Counter')}}: {{leads.total || 0}} records</div>
                    <div class="bg-white rounded-md shadow overflow-x-auto p-5">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                            <tr class="text-left font-bold bg-indigo-100 border-indigo-400 border-b-2">
                                <th class="pb-4 pt-6 px-6">Nome</th>
                                <th class="pb-4 pt-6 px-6">Cognome</th>
                                <th class="pb-4 pt-6 px-6">Email</th>
                                <th class="pb-4 pt-6 px-6">Telefono</th>
                                <th class="pb-4 pt-6 px-6">Comune</th>
                                <th class="pb-4 pt-6 px-6">Regione</th>
                                <th class="pb-4 pt-6 px-6">Provincia</th>
                                <th class="pb-4 pt-6 px-6" colspan="2">Descrizione</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="lead in leads.data" :key="lead.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                                <td class="border-t">
                                    <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="editLeadLink(lead.id)">
                                        {{ lead.name }}
                                        <icon v-if="lead.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                                    </Link>
                                </td>
                                <td class="border-t">
                                    <Link class="flex items-center px-6 py-4" :href="editLeadLink(lead.id)" tabindex="-1">
                                        {{ lead.lastname }}
                                    </Link>
                                </td>
                                <td class="border-t">
                                    <Link class="flex items-center px-6 py-4" :href="editLeadLink(lead.id)" tabindex="-1">
                                        {{ lead.email }}
                                    </Link>
                                </td>
                                <td class="border-t">
                                    <Link class="flex items-center px-6 py-4" :href="editLeadLink(lead.id)" tabindex="-1">
                                        {{ lead.phone }}
                                    </Link>
                                </td>
                                <td class="border-t">
                                    <Link class="flex items-center px-6 py-4" :href="editLeadLink(lead.id)" tabindex="-1">
                                        {{ lead.town }}
                                    </Link>
                                </td>
                                <td class="border-t">
                                    <Link class="flex items-center px-6 py-4" :href="editLeadLink(lead.id)" tabindex="-1">
                                        {{ lead.region_name }}
                                    </Link>
                                </td>
                                <td class="border-t">
                                    <Link class="flex items-center px-6 py-4" :href="editLeadLink(lead.id)" tabindex="-1">
                                        {{ lead.province_name }}
                                    </Link>
                                </td>
                                <td class="border-t">
                                    <Link class="flex items-center px-6 py-4" :href="editLeadLink(lead.id)" tabindex="-1">
                                        {{ lead.description }}
                                    </Link>
                                </td>

                                <td class="w-px border-t">
                                    <Link class="flex items-center px-4" :href="editLeadLink(lead.id)" tabindex="-1">
                                        <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="leads.total === 0">
                                <td class="px-6 py-4 border-t" colspan="4">No leads found.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination class="mt-6 p-6" :links="leads.links" />
                </div>
            </div>
        </div>
    </AdminAuthenticatedLayout>
</template>
