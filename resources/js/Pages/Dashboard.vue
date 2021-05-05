<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        You're logged in!
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <wallet-cards :wallets="wallets"></wallet-cards>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import WalletCards from "@/Components/WalletCards";
import WalletItem from "@/Components/WalletItem";

export default {
    components: {
        BreezeAuthenticatedLayout,
        WalletCards,
        WalletItem,
    },

    props: {
        auth: Object,
        errors: Object,
        wallets: Array,
    },
}
</script>
