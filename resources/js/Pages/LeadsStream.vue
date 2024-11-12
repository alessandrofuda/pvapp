<script setup>

import {useTrans} from "@/Composables/trans.js";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import {Head} from "@inertiajs/vue3";
import LeadCard from "@/Components/LeadCard.vue";

const props = defineProps({
    leads: Object
})

</script>
<template>
    <GuestLayout>
        <Head :title="useTrans('Available Leads')" />
        <div class="text-lg text-center my-3">{{useTrans('Available Leads')}}</div>

        <div class="filters my-5 py-4">Filters .. todo..</div>

        <div v-if="leads" v-for="lead in leads.data" :key="lead?.id" class="">
            <LeadCard :lead="lead"></LeadCard>
        </div>
    </GuestLayout>
</template>
