<script setup>
    const props = defineProps({
        modelValue: {
            type: String
        }
    });

    // Define the emits
    defineEmits(['update:modelValue', 'reset']);
</script>
<template>
    <div class="flex items-center">
        <div class="flex w-full bg-white rounded shadow">
            <input class="relative px-6 py-3 w-full focus:shadow-outline"
                   autocomplete="off"
                   type="text"
                   name="search"
                   placeholder="Search…"
                   :value="modelValue"
                   @input="$emit('update:modelValue', $event.target.value)" />
        </div>
        <button class="ml-3 text-gray-500 hover:text-gray-700 focus:text-indigo-500 text-sm" type="button" @click="$emit('reset')">Reset</button>
    </div>
</template>

