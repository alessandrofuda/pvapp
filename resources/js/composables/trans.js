import { usePage } from '@inertiajs/vue3';

export function useTrans( value ) {
    const translationsArray = usePage().props.translations;

    return translationsArray[value] != null ? translationsArray[value] : value;
}
