<script setup>
import {dateFormatter} from "../Composables/utilities.js";
import HideValueBlock from "@/Components/HideValueBlock.vue";

const props = defineProps({
    lead: Object
})
</script>
<template>
    <div class="border border-gray-300 rounded-md p-5 m-5 bg-gray-50 shadow-lg ">
        <div class="px-4 sm:px-0 text-center">
            <h3 class="text-base/7 text-gray-900">Richiesta per:</h3>
            <p class="mt-1 text-gray-500 font-semibold">Installazione Impianto Fotovoltaico</p>
        </div>
        <div class="my-6 border-y border-gray-300">
            <dl class="">

                todo ottimizzare per mobile
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900">ID</dt>
                    <dd class="mt-1 text-gray-700 sm:col-span-2 sm:mt-0 font-bold">{{ lead.id }}</dd>
                </div>
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900">Data richiesta:</dt>
                    <dd class="mt-1 text-gray-700 sm:col-span-2 sm:mt-0 font-bold">{{dateFormatter(lead.created_at, 'DD-MM-YYYY')}}</dd>
                </div>


                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0 border-t border-gray-300">
                    <dt class="font-medium text-gray-900">Nome</dt>
                    <dd class="mt-1 text-gray-700 sm:col-span-2 sm:mt-0">{{ lead.name }}</dd>
                </div>
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900">Cognome</dt>
                    <dd class="mt-1 text-gray-700 sm:col-span-2 sm:mt-0"><HideValueBlock/></dd>
                </div>
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900">E-mail</dt>
                    <dd class="mt-1 text-gray-700 sm:col-span-2 sm:mt-0"><HideValueBlock/></dd>
                </div>
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900">Telefono</dt>
                    <dd class="mt-1 text-gray-700 sm:col-span-2 sm:mt-0"><HideValueBlock/></dd>
                </div>


                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0  border-t border-gray-300">
                    <dt class="font-medium text-gray-900">Comune</dt>
                    <dd class="mt-1 text-gray-700 sm:col-span-2 sm:mt-0">{{ lead.area.town}}</dd>
                </div>
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900">Provincia</dt>
                    <dd class="mt-1 text-gray-700 sm:col-span-2 sm:mt-0">{{ lead.area.province_code}}</dd>
                </div>
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900">Regione</dt>
                    <dd class="mt-1 text-gray-700 sm:col-span-2 sm:mt-0">{{ lead.area.region_name}}</dd>
                </div>


                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0  border-t border-gray-300">
                    <dt class="font-medium text-gray-900">Testo della richiesta</dt>
                    <dd class="mt-1 text-gray-700 sm:col-span-2 sm:mt-0">{{ lead.description || 'Nessun dettaglio.'}}</dd>
                </div>

                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0  border-t border-gray-300">
                    <dt class="font-medium text-gray-900">Prezzo</dt>
                    <dd class="mt-1 text-gray-700 sm:col-span-2 sm:mt-0">{{ `€ ${lead.price}` || 'N.A.' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</template>
