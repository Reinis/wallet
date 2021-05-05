<template>
    <inertia-link :href="'/wallet/' + wallet.id + '/edit'">
        <div class="p-4 bg-gray-400 w-48 shadow border-b rounded">
            <div class="text-2xl font-bold">{{ wallet.name }}</div>
            <div>{{ wallet.description }}</div>
        </div>
    </inertia-link>
</template>

<script>
import {Link as InertiaLink} from '@inertiajs/inertia-vue3'

export default {
    name: "wallet-item",
    props: ['wallet'],
    components: {
        InertiaLink,
    }
}
</script>

<style scoped>

</style>
