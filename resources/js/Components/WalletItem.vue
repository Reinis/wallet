<template>
    <inertia-link :href="'/wallet/' + wallet.id">
        <div class="card">
            <div class="text-2xl font-bold">{{ wallet.name }}</div>
            <div>
                <span class="pr-2 font-bold">Balance:</span>
                <span :class="{
                        'text-green-600': wallet.balance.amount >= 0,
                        'text-red-500': wallet.balance.amount < 0,
                        'font-bold': true,
                    }"
                >
                {{ wallet.balance.formatted }}
                </span>
            </div>
            <div>{{ wallet.description }}</div>
            <inertia-link
                :href="'/wallet/' + wallet.id + '/edit'"
                class="card-button"
            >
                🖊
            </inertia-link>
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
