<script setup>

import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SearchFilter from "@/Components/SearchFilter.vue";
import {reactive} from "vue";

const form = reactive({
    search: filters.search,
    trashed: filters.trashed,
});

</script>

<template>
    <Head title="Operators" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Operators</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    ...see ping CRM > Organizations table...

                    <div class="flex items-center justify-between mb-6">
                        <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
                            <label class="block text-gray-700">Trashed:</label>
                            <select v-model="form.trashed" class="form-select mt-1 w-full">
                                <option :value="null" />
                                <option value="with">With Trashed</option>
                                <option value="only">Only Trashed</option>
                            </select>
                        </search-filter>
                        <!--Link class="btn-indigo" href="/organizations/create">
                            <span>Create</span>
                            <span class="hidden md:inline">&nbsp;Organization</span>
                        </Link--><!--todo-->
                    </div>


                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
